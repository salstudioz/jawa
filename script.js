document.addEventListener('DOMContentLoaded', function() {
    // Inisialisasi AOS
    AOS.init({
        offset: 100,
        duration: 800,
        easing: 'ease-in-out',
        once: true
    });

    // Mobile Menu Functionality
    const menuToggle = document.getElementById('mobile-menu');
    const navLinks = document.getElementById('nav-links');
    
    if(menuToggle && navLinks) {
        menuToggle.addEventListener('click', () => {
            navLinks.classList.toggle('active');
            menuToggle.innerHTML = navLinks.classList.contains('active') 
                ? '<i class="fas fa-times"></i>' 
                : '<i class="fas fa-bars"></i>';
        });
        
        // Close menu when clicking outside
        document.addEventListener('click', (e) => {
            if(!navLinks.contains(e.target) && !menuToggle.contains(e.target)) {
                navLinks.classList.remove('active');
                menuToggle.innerHTML = '<i class="fas fa-bars"></i>';
            }
        });
    }

    // Mobile Dropdown Toggle
    const dropdowns = document.querySelectorAll('.dropdown');
    dropdowns.forEach(dropdown => {
        dropdown.addEventListener('click', (e) => {
            if(window.innerWidth <= 768) {
                e.preventDefault();
                dropdown.classList.toggle('active');
            }
        });
    });

    // FAQ Accordion
    const faqQuestions = document.querySelectorAll('.faq-question');
    faqQuestions.forEach(question => {
        question.addEventListener('click', () => {
            const answer = question.nextElementSibling;
            const icon = question.querySelector('i');
            
            if(answer.classList.contains('active')) {
                answer.classList.remove('active');
                icon.classList.remove('fa-chevron-up');
                icon.classList.add('fa-chevron-down');
            } else {
                // Close other FAQs
                document.querySelectorAll('.faq-answer').forEach(a => {
                    a.classList.remove('active');
                    a.previousElementSibling.querySelector('i').classList.remove('fa-chevron-up');
                    a.previousElementSibling.querySelector('i').classList.add('fa-chevron-down');
                });
                
                answer.classList.add('active');
                icon.classList.remove('fa-chevron-down');
                icon.classList.add('fa-chevron-up');
            }
        });
    });

    // Smooth scroll for anchor links
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function(e) {
            e.preventDefault();
            const targetId = this.getAttribute('href');
            if(targetId === '#') return;
            
            const targetElement = document.querySelector(targetId);
            if(targetElement) {
                window.scrollTo({
                    top: targetElement.offsetTop - 80,
                    behavior: 'smooth'
                });
                
                // Close mobile menu if open
                if(navLinks) {
                    navLinks.classList.remove('active');
                    menuToggle.innerHTML = '<i class="fas fa-bars"></i>';
                }
            }
        });
    });

    // Form validation
    const forms = document.querySelectorAll('form');
    forms.forEach(form => {
        form.addEventListener('submit', function(e) {
            const requiredFields = this.querySelectorAll('[required]');
            let isValid = true;
            
            requiredFields.forEach(field => {
                if(!field.value.trim()) {
                    isValid = false;
                    field.style.borderColor = 'red';
                } else {
                    field.style.borderColor = '';
                }
            });
            
            if(!isValid) {
                e.preventDefault();
                alert('Harap isi semua field yang wajib diisi!');
            }
        });
    });

    // Order button functionality
    const orderButtons = document.querySelectorAll('.order-btn');
    orderButtons.forEach(btn => {
        if(!btn.hasAttribute('href')) {
            btn.addEventListener('click', function() {
                const packageName = this.closest('.package-card').querySelector('.package-title').textContent;
                alert(`Anda akan memesan paket: ${packageName}\n\nFitur pemesanan lengkap akan segera hadir!`);
            });
        }
    });

    // Custom Dropdown Functionality
    const customSelects = document.querySelectorAll('.custom-select');
    
    customSelects.forEach(select => {
        const wrapper = select.closest('.custom-select-wrapper');
        const arrow = wrapper.querySelector('.select-arrow');
        
        // Change arrow on focus
        select.addEventListener('focus', () => {
            if(arrow) {
                arrow.classList.remove('fa-chevron-down');
                arrow.classList.add('fa-chevron-up');
            }
        });
        
        select.addEventListener('blur', () => {
            if(arrow) {
                arrow.classList.remove('fa-chevron-up');
                arrow.classList.add('fa-chevron-down');
            }
        });
        
        // Change on select
        select.addEventListener('change', function() {
            if(this.value) {
                this.style.background = 'rgba(255, 255, 255, 0.3)';
            } else {
                this.style.background = 'rgba(255, 255, 255, 0.2)';
            }
        });
    });

    // Paket Custom Form Validation
    const customForm = document.getElementById('customForm');
    if(customForm) {
        customForm.addEventListener('submit', function(e) {
            const selects = this.querySelectorAll('.custom-select[required]');
            let isValid = true;
            
            selects.forEach(select => {
                if(!select.value) {
                    isValid = false;
                    select.style.borderColor = '#ff6b6b';
                    select.style.boxShadow = '0 0 0 2px rgba(255, 107, 107, 0.2)';
                } else {
                    select.style.borderColor = 'rgba(255, 255, 255, 0.4)';
                    select.style.boxShadow = 'none';
                }
            });
            
            if(!isValid) {
                e.preventDefault();
                alert('Harap pilih semua opsi sebelum mencari!');
            }
        });
    }

    // Mobile responsive adjustments
    function adjustForMobile() {
        if(window.innerWidth <= 768) {
            // Adjust search container for mobile
            const searchContainer = document.querySelector('.search-container');
            if(searchContainer) {
                searchContainer.style.flexDirection = 'column';
                searchContainer.style.gap = '20px';
            }
            
            const searchGroups = document.querySelectorAll('.search-group');
            searchGroups.forEach(group => {
                group.style.borderRight = 'none';
                group.style.borderBottom = '1px solid rgba(255, 255, 255, 0.3)';
                group.style.paddingBottom = '15px';
                group.style.width = '100%';
            });
            
            // Show nav buttons in mobile menu
            const navButtons = document.querySelector('.nav-buttons');
            if(navButtons && !document.querySelector('.nav-buttons.mobile')) {
                const mobileNavButtons = navButtons.cloneNode(true);
                mobileNavButtons.classList.add('mobile');
                mobileNavButtons.style.display = 'flex';
                mobileNavButtons.style.flexDirection = 'column';
                mobileNavButtons.style.gap = '10px';
                mobileNavButtons.style.marginTop = '20px';
                document.querySelector('.nav-links').appendChild(mobileNavButtons);
            }
        }
    }

    // Initial adjustment
    adjustForMobile();
    
    // Window resize listener
    window.addEventListener('resize', adjustForMobile);
});


// Paket Custom Form Validation
const customForm = document.getElementById('customForm');
if(customForm) {
    customForm.addEventListener('submit', function(e) {
        const selects = this.querySelectorAll('.custom-select[required]');
        let isValid = true;
        
        selects.forEach(select => {
            if(!select.value) {
                isValid = false;
                select.style.borderColor = '#ff6b6b';
                select.style.boxShadow = '0 0 0 2px rgba(255, 107, 107, 0.2)';
            } else {
                select.style.borderColor = 'rgba(255, 255, 255, 0.4)';
                select.style.boxShadow = 'none';
            }
        });
        
        if(!isValid) {
            e.preventDefault();
            alert('Harap pilih semua opsi sebelum mencari!');
        }
    });
}

// My Orders page functionality
document.addEventListener('DOMContentLoaded', function() {
    // Print invoice button
    const printButtons = document.querySelectorAll('.print-invoice');
    printButtons.forEach(btn => {
        btn.addEventListener('click', function(e) {
            e.preventDefault();
            const orderId = this.getAttribute('data-order-id');
            window.open(`print_invoice.php?id=${orderId}`, '_blank');
        });
    });
    
    // Order status filter (jika ada)
    const statusFilter = document.getElementById('status-filter');
    if(statusFilter) {
        statusFilter.addEventListener('change', function() {
            const status = this.value;
            const orderCards = document.querySelectorAll('.order-card');
            
            orderCards.forEach(card => {
                const cardStatus = card.getAttribute('data-status');
                if(status === 'all' || cardStatus === status) {
                    card.style.display = 'block';
                } else {
                    card.style.display = 'none';
                }
            });
        });
    }
});

// Quick order functionality
function initQuickOrder() {
    const quickOrderButtons = document.querySelectorAll('.quick-order-btn');
    quickOrderButtons.forEach(btn => {
        btn.addEventListener('click', function() {
            const paketId = this.getAttribute('data-paket-id');
            const paketNama = this.getAttribute('data-paket-nama');
            
            const jumlahOrang = prompt(`Pesan paket: ${paketNama}\n\nMasukkan jumlah orang:`, "2");
            
            if(jumlahOrang && !isNaN(jumlahOrang) && jumlahOrang > 0) {
                if(confirm(`Konfirmasi pesanan:\nPaket: ${paketNama}\nJumlah: ${jumlahOrang} orang\n\nLanjutkan?`)) {
                    window.location.href = `orders.php?paket=${paketId}&jumlah=${jumlahOrang}`;
                }
            } else if(jumlahOrang !== null) {
                alert('Masukkan jumlah orang yang valid!');
            }
        });
    });
}

// Package detail modal
function initPackageDetailModal() {
    const detailButtons = document.querySelectorAll('.detail-btn');
    const modal = document.getElementById('packageDetailModal');
    
    if(modal) {
        const closeBtn = modal.querySelector('.close-modal');
        
        detailButtons.forEach(btn => {
            btn.addEventListener('click', function() {
                const paketId = this.getAttribute('data-paket-id');
                // Load package details via AJAX
                loadPackageDetails(paketId);
                modal.style.display = 'block';
            });
        });
        
        closeBtn.addEventListener('click', () => {
            modal.style.display = 'none';
        });
        
        window.addEventListener('click', (e) => {
            if(e.target == modal) {
                modal.style.display = 'none';
            }
        });
    }
}

