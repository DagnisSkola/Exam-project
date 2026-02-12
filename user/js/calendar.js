// open income modal
function openIncomeModal() {
    const modal = document.getElementById('incomeModal');
    modal.classList.add('modal-open');
    document.body.style.overflow = 'hidden';
}

// close income modal
function closeIncomeModal() {
    const modal = document.getElementById('incomeModal');
    modal.classList.remove('modal-open');
    document.body.style.overflow = 'auto';
}

// open expense modal
function openExpenseModal() {
    const modal = document.getElementById('expenseModal');
    modal.classList.add('modal-open');
    document.body.style.overflow = 'hidden';
}

// close expense modal
function closeExpenseModal() {
    const modal = document.getElementById('expenseModal');
    modal.classList.remove('modal-open');
    document.body.style.overflow = 'auto';
}

// open day details
function openDayModal(day, month, year) {
    if (!transactionsData[day]) {
        return; 
    }
    
    const modal = document.getElementById('dayModal');
    const title = document.getElementById('dayModalTitle');
    const content = document.getElementById('dayModalContent');
    const monthNames = ['', 'Janvāris', 'Februāris', 'Marts', 'Aprīlis', 'Maijs', 'Jūnijs', 
                        'Jūlijs', 'Augusts', 'Septembris', 'Oktobris', 'Novembris', 'Decembris'];
    title.textContent = `${day}. ${monthNames[month]}, ${year}`;
    
    const transactions = transactionsData[day];
    let html = '';
    
    transactions.forEach(transaction => {
        const typeClass = transaction.type === 'income' ? 'income' : 'expense';
        const typeLabel = transaction.type === 'income' ? 'Ienākums' : 'Izdevums';
        const sign = transaction.type === 'income' ? '+' : '-';
        const recurringBadge = transaction.is_recurring_display ? '<span class="recurring-badge">🔄 Ikmēneša</span>' : '';
        
        html += `
            <div class="transaction-item ${typeClass}">
                <div class="transaction-info">
                    <div class="transaction-description">${transaction.description} ${recurringBadge}</div>
                    <div class="transaction-type">${typeLabel}</div>
                </div>
                <div class="transaction-amount">${sign}€${parseFloat(transaction.amount).toFixed(2)}</div>
            </div>
        `;
    });
    
    content.innerHTML = html;
    
    modal.classList.add('modal-open');
    document.body.style.overflow = 'hidden';
}

// close day modal
function closeDayModal() {
    const modal = document.getElementById('dayModal');
    modal.classList.remove('modal-open');
    document.body.style.overflow = 'auto';
}

// close modal if click outside
window.addEventListener('click', function(e) {
    const incomeModal = document.getElementById('incomeModal');
    const expenseModal = document.getElementById('expenseModal');
    const dayModal = document.getElementById('dayModal');
    
    if (e.target === incomeModal) {
        closeIncomeModal();
    }
    if (e.target === expenseModal) {
        closeExpenseModal();
    }
    if (e.target === dayModal) {
        closeDayModal();
    }
});

// close modal with esc
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        closeIncomeModal();
        closeExpenseModal();
        closeDayModal();
    }
});

// animations for cards
document.addEventListener('DOMContentLoaded', function() {
    const statCards = document.querySelectorAll('.stat-card');
    
    statCards.forEach((card, index) => {
        setTimeout(() => {
            card.style.opacity = '0';
            card.style.transform = 'translateY(20px)';
            
            setTimeout(() => {
                card.style.transition = 'all 0.5s ease-out';
                card.style.opacity = '1';
                card.style.transform = 'translateY(0)';
            }, 50);
        }, index * 100);
    });
});

// animations for days
const calendarDays = document.querySelectorAll('.calendar-day:not(.calendar-day-empty)');
calendarDays.forEach(day => {
    day.addEventListener('mouseenter', function() {
        if (!this.classList.contains('calendar-day-empty')) {
            this.style.transform = 'scale(1.05)';
        }
    });
    
    day.addEventListener('mouseleave', function() {
        this.style.transform = 'scale(1)';
    });
});