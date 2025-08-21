<?php

namespace App\Services;

use App\Models\Assessment;
use App\Models\Question;
use App\Models\StudentAssessment;
use App\Models\User;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class AssessmentAnalyticsService
{
    /**
     * Gerar relatório completo de performance
     */
    public function generatePerformanceReport(Assessment $assessment)
    {
        return Cache::remember("performance_report_{$assessment->id}", 1800, function () use ($assessment) {
            $attempts = $assessment->studentAssessments()->where('status', 'completed')->get();
            
            if ($attempts->isEmpty()) {
                return ['message' => 'Nenhuma tentativa concluída ainda.'];
            }
            
            return [
                'overview' => $this->getOverviewStats($attempts, $assessment),
                'score_analysis' => $this->getScoreAnalysis($attempts, $assessment),
                'time_analysis' => $this->getTimeAnalysis($attempts),
                'question_analysis' => $this->getQuestionAnalysis($assessment),
                'student_performance' => $this->getStudentPerformance($attempts),
                'trends' => $this->getTrends($assessment),
                'recommendations' => $this->generateRecommendations($assessment, $attempts)
            ];
        });
    }
    
    /**
     * Estatísticas gerais
     */
    private function getOverviewStats($attempts, $assessment)
    {
        $scores = $attempts->pluck('score');
        
        return [
            'total_attempts' => $attempts->count(),
            'average_score' => round($scores->avg(), 2),
            'median_score' => $this->calculateMedian($scores->toArray()),
            'highest_score' => $scores->max(),
            'lowest_score' => $scores->min(),
            'standard_deviation' => $this->calculateStandardDeviation($scores->toArray()),
            'completion_rate' => $this->getCompletionRate($assessment),
            'pass_rate' => $this->getPassRate($attempts, $assessment)
        ];
    }
    
    /**
     * Análise de distribuição de notas
     */
    private function getScoreAnalysis($attempts, $assessment)
    {
        $maxScore = $assessment->max_score;
        $ranges = [
            '0-20%' => 0, '21-40%' => 0, '41-60%' => 0, '61-80%' => 0, '81-100%' => 0
        ];
        
        foreach ($attempts as $attempt) {
            $percentage = ($attempt->score / $maxScore) * 100;
            if ($percentage <= 20) $ranges['0-20%']++;
            elseif ($percentage <= 40) $ranges['21-40%']++;
            elseif ($percentage <= 60) $ranges['41-60%']++;
            elseif ($percentage <= 80) $ranges['61-80%']++;
            else $ranges['81-100%']++;
        }
        
        return [
            'distribution' => $ranges,
            'percentiles' => $this->calculatePercentiles($attempts->pluck('score')->toArray()),
            'outliers' => $this->detectOutliers($attempts->pluck('score')->toArray())
        ];
    }
    
    /**
     * Análise de tempo
     */
    private function getTimeAnalysis($attempts)
    {
        $times = $attempts->filter(function ($attempt) {
            return $attempt->started_at && $attempt->finished_at;
        })->map(function ($attempt) {
            return $attempt->finished_at->diffInMinutes($attempt->started_at);
        });
        
        if ($times->isEmpty()) {
            return ['message' => 'Dados de tempo insuficientes.'];
        }
        
        return [
            'average_time' => round($times->avg(), 1),
            'median_time' => $this->calculateMedian($times->toArray()),
            'fastest_completion' => $times->min(),
            'slowest_completion' => $times->max(),
            'time_distribution' => $this->getTimeDistribution($times->toArray())
        ];
    }
    
    /**
     * Análise por questão
     */
    private function getQuestionAnalysis($assessment)
    {
        $questionStats = [];
        
        foreach ($assessment->questions as $question) {
            $answers = $question->studentAnswers()
                ->whereHas('studentAssessment', function ($q) use ($assessment) {
                    $q->where('assessment_id', $assessment->id)
                      ->where('status', 'completed');
                })->get();
            
            $totalAnswers = $answers->count();
            $correctAnswers = $answers->where('is_correct', true)->count();
            
            $questionStats[] = [
                'question_id' => $question->id,
                'title' => $question->title,
                'difficulty' => $question->difficulty,
                'type' => $question->type,
                'total_answers' => $totalAnswers,
                'correct_answers' => $correctAnswers,
                'accuracy_rate' => $totalAnswers > 0 ? round(($correctAnswers / $totalAnswers) * 100, 1) : 0,
                'discrimination_index' => $this->calculateDiscriminationIndex($question, $assessment),
                'point_biserial' => $this->calculatePointBiserial($question, $assessment)
            ];
        }
        
        return $questionStats;
    }
    
    /**
     * Performance individual dos estudantes
     */
    private function getStudentPerformance($attempts)
    {
        return $attempts->map(function ($attempt) {
            $student = $attempt->student;
            $timeSpent = $attempt->started_at && $attempt->finished_at 
                ? $attempt->finished_at->diffInMinutes($attempt->started_at) 
                : null;
            
            return [
                'student_id' => $student->id,
                'student_name' => $student->name,
                'score' => $attempt->score,
                'percentage' => round(($attempt->score / $attempt->assessment->max_score) * 100, 1),
                'time_spent' => $timeSpent,
                'started_at' => $attempt->started_at,
                'finished_at' => $attempt->finished_at,
                'answers_count' => $attempt->answers->count(),
                'correct_answers' => $attempt->answers->where('is_correct', true)->count()
            ];
        })->sortByDesc('score')->values();
    }
    
    /**
     * Análise de tendências
     */
    private function getTrends($assessment)
    {
        $attempts = $assessment->studentAssessments()
            ->where('status', 'completed')
            ->orderBy('finished_at')
            ->get();
        
        if ($attempts->count() < 5) {
            return ['message' => 'Dados insuficientes para análise de tendências.'];
        }
        
        // Agrupar por semana
        $weeklyData = $attempts->groupBy(function ($attempt) {
            return $attempt->finished_at->format('Y-W');
        })->map(function ($weekAttempts) {
            return [
                'count' => $weekAttempts->count(),
                'average_score' => round($weekAttempts->avg('score'), 2),
                'week' => $weekAttempts->first()->finished_at->format('Y-W')
            ];
        });
        
        return [
            'weekly_performance' => $weeklyData->values(),
            'score_trend' => $this->calculateTrend($weeklyData->pluck('average_score')->toArray()),
            'participation_trend' => $this->calculateTrend($weeklyData->pluck('count')->toArray())
        ];
    }
    
    /**
     * Gerar recomendações
     */
    private function generateRecommendations($assessment, $attempts)
    {
        $recommendations = [];
        $avgScore = $attempts->avg('score');
        $maxScore = $assessment->max_score;
        $avgPercentage = ($avgScore / $maxScore) * 100;
        
        // Recomendações baseadas na performance geral
        if ($avgPercentage < 60) {
            $recommendations[] = [
                'type' => 'warning',
                'category' => 'Performance Geral',
                'message' => 'A média geral está baixa. Considere revisar o conteúdo ou a dificuldade das questões.',
                'priority' => 'high'
            ];
        }
        
        // Análise de questões problemáticas
        $problematicQuestions = $assessment->questions->filter(function ($question) use ($assessment) {
            $accuracy = $this->getQuestionAccuracy($question, $assessment);
            return $accuracy < 30; // Menos de 30% de acerto
        });
        
        if ($problematicQuestions->count() > 0) {
            $recommendations[] = [
                'type' => 'error',
                'category' => 'Questões Problemáticas',
                'message' => "Existem {$problematicQuestions->count()} questão(ões) com baixa taxa de acerto. Revise o conteúdo ou clareza.",
                'priority' => 'high'
            ];
        }
        
        // Recomendações de tempo
        $avgTime = $this->getAverageCompletionTime($attempts);
        $expectedTime = $assessment->duration_minutes;
        
        if ($avgTime < $expectedTime * 0.5) {
            $recommendations[] = [
                'type' => 'info',
                'category' => 'Tempo',
                'message' => 'Alunos estão terminando muito rapidamente. Considere adicionar mais questões ou aumentar a complexidade.',
                'priority' => 'medium'
            ];
        }
        
        return $recommendations;
    }
    
    // Métodos auxiliares de cálculo
    private function calculateMedian(array $values)
    {
        if (empty($values)) return 0;
        sort($values);
        $count = count($values);
        $middle = floor($count / 2);
        return $count % 2 ? $values[$middle] : ($values[$middle - 1] + $values[$middle]) / 2;
    }
    
    private function calculateStandardDeviation(array $values)
    {
        if (count($values) < 2) return 0;
        $mean = array_sum($values) / count($values);
        $variance = array_sum(array_map(function($x) use ($mean) { return pow($x - $mean, 2); }, $values)) / count($values);
        return sqrt($variance);
    }
    
    private function calculatePercentiles(array $values)
    {
        if (empty($values)) return [];
        sort($values);
        $count = count($values);
        
        return [
            'p25' => $values[floor($count * 0.25)],
            'p50' => $values[floor($count * 0.50)],
            'p75' => $values[floor($count * 0.75)],
            'p90' => $values[floor($count * 0.90)]
        ];
    }
    
    private function detectOutliers(array $values)
    {
        if (count($values) < 4) return [];
        
        $percentiles = $this->calculatePercentiles($values);
        $iqr = $percentiles['p75'] - $percentiles['p25'];
        $lowerBound = $percentiles['p25'] - 1.5 * $iqr;
        $upperBound = $percentiles['p75'] + 1.5 * $iqr;
        
        return array_filter($values, function($value) use ($lowerBound, $upperBound) {
            return $value < $lowerBound || $value > $upperBound;
        });
    }
    
    private function getCompletionRate($assessment)
    {
        $totalStarted = $assessment->studentAssessments()->count();
        $totalCompleted = $assessment->studentAssessments()->where('status', 'completed')->count();
        
        return $totalStarted > 0 ? round(($totalCompleted / $totalStarted) * 100, 1) : 0;
    }
    
    private function getPassRate($attempts, $assessment)
    {
        $passingScore = ($assessment->settings['passing_score'] ?? 60) / 100 * $assessment->max_score;
        $passed = $attempts->where('score', '>=', $passingScore)->count();
        
        return $attempts->count() > 0 ? round(($passed / $attempts->count()) * 100, 1) : 0;
    }
    
    private function getTimeDistribution(array $times)
    {
        $ranges = ['0-15min' => 0, '16-30min' => 0, '31-60min' => 0, '60+min' => 0];
        
        foreach ($times as $time) {
            if ($time <= 15) $ranges['0-15min']++;
            elseif ($time <= 30) $ranges['16-30min']++;
            elseif ($time <= 60) $ranges['31-60min']++;
            else $ranges['60+min']++;
        }
        
        return $ranges;
    }
    
    private function calculateDiscriminationIndex($question, $assessment)
    {
        // Implementar cálculo do índice de discriminação
        // Compara performance dos 27% melhores vs 27% piores alunos
        return 0; // Placeholder
    }
    
    private function calculatePointBiserial($question, $assessment)
    {
        // Implementar correlação ponto-bisserial
        // Correlação entre acerto na questão e score total
        return 0; // Placeholder
    }
    
    private function getQuestionAccuracy($question, $assessment)
    {
        $answers = $question->studentAnswers()
            ->whereHas('studentAssessment', function ($q) use ($assessment) {
                $q->where('assessment_id', $assessment->id)->where('status', 'completed');
            })->get();
        
        if ($answers->isEmpty()) return 0;
        
        return ($answers->where('is_correct', true)->count() / $answers->count()) * 100;
    }
    
    private function getAverageCompletionTime($attempts)
    {
        $times = $attempts->filter(function ($attempt) {
            return $attempt->started_at && $attempt->finished_at;
        })->map(function ($attempt) {
            return $attempt->finished_at->diffInMinutes($attempt->started_at);
        });
        
        return $times->avg() ?? 0;
    }
    
    private function calculateTrend(array $values)
    {
        if (count($values) < 2) return 'stable';
        
        $first = array_slice($values, 0, ceil(count($values) / 2));
        $second = array_slice($values, floor(count($values) / 2));
        
        $firstAvg = array_sum($first) / count($first);
        $secondAvg = array_sum($second) / count($second);
        
        $change = (($secondAvg - $firstAvg) / $firstAvg) * 100;
        
        if ($change > 5) return 'improving';
        elseif ($change < -5) return 'declining';
        else return 'stable';
    }
}