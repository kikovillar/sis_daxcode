import './bootstrap';

import Alpine from 'alpinejs';

// Configurar Alpine.js
window.Alpine = Alpine;

// Componentes Alpine.js para o sistema de avaliação
Alpine.data('assessmentTimer', (initialTime) => ({
    timeRemaining: initialTime,
    isWarning: false,
    isDanger: false,
    
    init() {
        this.startTimer();
    },
    
    startTimer() {
        const timer = setInterval(() => {
            if (this.timeRemaining > 0) {
                this.timeRemaining--;
                this.updateWarningStates();
                this.updateDisplay();
            } else {
                clearInterval(timer);
                this.handleTimeExpired();
            }
        }, 1000);
    },
    
    updateWarningStates() {
        this.isWarning = this.timeRemaining <= 600 && this.timeRemaining > 300; // 10-5 minutos
        this.isDanger = this.timeRemaining <= 300; // 5 minutos
    },
    
    updateDisplay() {
        const minutes = Math.floor(this.timeRemaining / 60);
        const seconds = this.timeRemaining % 60;
        const display = `${minutes.toString().padStart(2, '0')}:${seconds.toString().padStart(2, '0')}`;
        
        const timerElement = document.getElementById('timer-display');
        if (timerElement) {
            timerElement.textContent = display;
            
            // Aplicar classes de estilo baseadas no tempo restante
            timerElement.className = 'text-lg font-mono font-bold ';
            if (this.isDanger) {
                timerElement.className += 'timer-danger';
            } else if (this.isWarning) {
                timerElement.className += 'timer-warning';
            } else {
                timerElement.className += 'text-blue-600';
            }
        }
    },
    
    handleTimeExpired() {
        // Disparar evento para o Livewire
        if (window.Livewire) {
            window.Livewire.dispatch('timeExpired');
        }
        
        // Mostrar alerta
        this.showTimeExpiredAlert();
    },
    
    showTimeExpiredAlert() {
        const alert = document.createElement('div');
        alert.className = 'fixed top-4 right-4 bg-red-600 text-white px-6 py-4 rounded-lg shadow-lg z-50';
        alert.innerHTML = `
            <div class="flex items-center">
                <svg class="w-6 h-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <span>Tempo esgotado! Finalizando avaliação...</span>
            </div>
        `;
        
        document.body.appendChild(alert);
        
        setTimeout(() => {
            alert.remove();
        }, 5000);
    }
}));

// Componente para auto-salvamento
Alpine.data('autoSave', (interval = 30000) => ({
    init() {
        setInterval(() => {
            if (window.Livewire) {
                window.Livewire.dispatch('autoSave');
            }
        }, interval);
    }
}));

// Componente para confirmação de ações
Alpine.data('confirmAction', () => ({
    showConfirm: false,
    message: '',
    action: null,
    
    confirm(message, callback) {
        this.message = message;
        this.action = callback;
        this.showConfirm = true;
    },
    
    execute() {
        if (this.action) {
            this.action();
        }
        this.cancel();
    },
    
    cancel() {
        this.showConfirm = false;
        this.message = '';
        this.action = null;
    }
}));

// Componente para notificações
Alpine.data('notifications', () => ({
    notifications: [],
    
    init() {
        // Escutar eventos do Livewire
        window.addEventListener('show-alert', (event) => {
            this.addNotification(event.detail.type, event.detail.message);
        });
    },
    
    addNotification(type, message) {
        const id = Date.now();
        this.notifications.push({ id, type, message });
        
        // Auto-remover após 5 segundos
        setTimeout(() => {
            this.removeNotification(id);
        }, 5000);
    },
    
    removeNotification(id) {
        this.notifications = this.notifications.filter(n => n.id !== id);
    }
}));

// Componente para drag and drop (para reordenar questões)
Alpine.data('dragAndDrop', () => ({
    draggedElement: null,
    
    dragStart(event, element) {
        this.draggedElement = element;
        event.dataTransfer.effectAllowed = 'move';
    },
    
    dragOver(event) {
        event.preventDefault();
        event.dataTransfer.dropEffect = 'move';
    },
    
    drop(event, targetElement) {
        event.preventDefault();
        
        if (this.draggedElement && this.draggedElement !== targetElement) {
            // Implementar lógica de reordenação
            this.reorderElements(this.draggedElement, targetElement);
        }
        
        this.draggedElement = null;
    },
    
    reorderElements(source, target) {
        // Esta função seria implementada conforme a necessidade específica
        console.log('Reordenando elementos:', source, target);
    }
}));

// Utilitários globais
window.assessmentUtils = {
    // Formatar tempo em MM:SS
    formatTime(seconds) {
        const minutes = Math.floor(seconds / 60);
        const remainingSeconds = seconds % 60;
        return `${minutes.toString().padStart(2, '0')}:${remainingSeconds.toString().padStart(2, '0')}`;
    },
    
    // Calcular porcentagem
    calculatePercentage(value, total) {
        if (total === 0) return 0;
        return Math.round((value / total) * 100);
    },
    
    // Debounce para auto-salvamento
    debounce(func, wait) {
        let timeout;
        return function executedFunction(...args) {
            const later = () => {
                clearTimeout(timeout);
                func(...args);
            };
            clearTimeout(timeout);
            timeout = setTimeout(later, wait);
        };
    },
    
    // Confirmar ação com modal
    confirmAction(message, callback) {
        if (confirm(message)) {
            callback();
        }
    },
    
    // Mostrar notificação toast
    showToast(message, type = 'info') {
        const toast = document.createElement('div');
        toast.className = `fixed bottom-4 right-4 px-6 py-4 rounded-lg shadow-lg z-50 alert-${type}`;
        toast.textContent = message;
        
        document.body.appendChild(toast);
        
        setTimeout(() => {
            toast.remove();
        }, 3000);
    }
};

// Inicializar Alpine.js
Alpine.start();

// Event listeners globais
document.addEventListener('DOMContentLoaded', function() {
    // Prevenir envio de formulário com Enter em campos de texto
    document.addEventListener('keydown', function(event) {
        if (event.key === 'Enter' && event.target.tagName === 'INPUT' && event.target.type === 'text') {
            event.preventDefault();
        }
    });
    
    // Aviso antes de sair da página durante uma avaliação
    if (document.querySelector('[data-assessment-active]')) {
        window.addEventListener('beforeunload', function(event) {
            event.preventDefault();
            event.returnValue = 'Você tem certeza que deseja sair? Seu progresso pode ser perdido.';
            return event.returnValue;
        });
    }
    
    // Auto-focus no primeiro campo de formulário
    const firstInput = document.querySelector('input:not([type="hidden"]):not([disabled])');
    if (firstInput) {
        firstInput.focus();
    }
});