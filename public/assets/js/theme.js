// Theme Toggle
document.addEventListener('DOMContentLoaded', function() {
    // Navbar Scroll Effect
    const navbar = document.querySelector('.navbar');
    let lastScroll = 0;
    
    window.addEventListener('scroll', () => {
        const currentScroll = window.pageYOffset;
        
        if (currentScroll <= 0) {
            navbar.classList.remove('navbar-scrolled', 'navbar-hidden');
            return;
        }
        
        if (currentScroll > lastScroll && !navbar.classList.contains('navbar-hidden')) {
            // Scroll Down
            navbar.classList.remove('navbar-scrolled');
            navbar.classList.add('navbar-hidden');
            navbar.style.transform = 'translateY(-100%)';
        } else if (currentScroll < lastScroll && navbar.classList.contains('navbar-hidden')) {
            // Scroll Up
            navbar.classList.remove('navbar-hidden');
            navbar.classList.add('navbar-scrolled');
            navbar.style.transform = 'translateY(0)';
        }
        
        lastScroll = currentScroll;
    });
    
    // Enhanced Smooth Scroll
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function (e) {
            e.preventDefault();
            const target = document.querySelector(this.getAttribute('href'));
            if (target) {
                const headerOffset = 100;
                const elementPosition = target.getBoundingClientRect().top;
                const offsetPosition = elementPosition - headerOffset;

                window.scrollBy({
                    top: offsetPosition,
                    behavior: 'smooth'
                });

                // Add highlight effect
                target.classList.add('highlight-target');
                setTimeout(() => {
                    target.classList.remove('highlight-target');
                }, 2000);
            }
        });
    });
    
    // Enhanced Parallax Effect
    const parallaxElements = document.querySelectorAll('.parallax');
    window.addEventListener('scroll', () => {
        const scrolled = window.pageYOffset;
        
        parallaxElements.forEach(element => {
            const speed = element.dataset.speed || 0.5;
            const yPos = -(scrolled * speed);
            element.style.transform = `translate3d(0, ${yPos}px, 0)`;
        });
    });
    
    // Intersection Observer for Enhanced Animations
    const observerOptions = {
        root: null,
        rootMargin: '0px',
        threshold: 0.1
    };
    
    const enhancedObserver = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add('animate-in');
                
                // Add staggered animation for child elements
                if (entry.target.classList.contains('stagger-children')) {
                    const children = entry.target.children;
                    Array.from(children).forEach((child, index) => {
                        child.style.transitionDelay = `${index * 0.1}s`;
                        child.classList.add('animate-in');
                    });
                }
                
                // Add counter animation for numbers
                if (entry.target.classList.contains('counter')) {
                    const target = parseInt(entry.target.dataset.target);
                    let count = 0;
                    const duration = 2000; // 2 seconds
                    const increment = target / (duration / 16); // 60fps
                    
                    const updateCount = () => {
                        count += increment;
                        if (count < target) {
                            entry.target.innerText = Math.ceil(count);
                            requestAnimationFrame(updateCount);
                        } else {
                            entry.target.innerText = target;
                        }
                    };
                    
                    updateCount();
                }
            }
        });
    }, observerOptions);
    
    // Observe elements with animation classes
    document.querySelectorAll('.fade-in-up, .fade-in-left, .fade-in-right, .stagger-children, .counter').forEach(element => {
        enhancedObserver.observe(element);
    });
    
    // Add floating effect to cards with class
    document.querySelectorAll('.card.floating').forEach(card => {
        let isHovered = false;
        let rafId = null;
        
        const lerp = (start, end, factor) => {
            return start + (end - start) * factor;
        };
        
        card.addEventListener('mousemove', e => {
            isHovered = true;
            const rect = card.getBoundingClientRect();
            const x = e.clientX - rect.left;
            const y = e.clientY - rect.top;
            
            const centerX = rect.width / 2;
            const centerY = rect.height / 2;
            
            const targetRotateX = (y - centerY) / 20;
            const targetRotateY = (centerX - x) / 20;
            
            const animate = () => {
                if (!isHovered) return;
                
                const currentRotateX = parseFloat(card.style.getPropertyValue('--rotateX') || 0);
                const currentRotateY = parseFloat(card.style.getPropertyValue('--rotateY') || 0);
                
                const newRotateX = lerp(currentRotateX, targetRotateX, 0.1);
                const newRotateY = lerp(currentRotateY, targetRotateY, 0.1);
                
                card.style.setProperty('--rotateX', newRotateX);
                card.style.setProperty('--rotateY', newRotateY);
                card.style.transform = `perspective(1000px) rotateX(${newRotateX}deg) rotateY(${newRotateY}deg)`;
                
                rafId = requestAnimationFrame(animate);
            };
            
            if (!rafId) {
                rafId = requestAnimationFrame(animate);
            }
        });
        
        card.addEventListener('mouseleave', () => {
            isHovered = false;
            cancelAnimationFrame(rafId);
            rafId = null;
            
            card.style.transition = 'transform 0.5s ease';
            card.style.transform = 'perspective(1000px) rotateX(0) rotateY(0)';
            setTimeout(() => {
                card.style.transition = '';
            }, 500);
        });
    });
    
    // Add loading state to buttons
    document.querySelectorAll('.btn').forEach(button => {
        button.addEventListener('click', function(e) {
            if (this.classList.contains('btn-submit')) {
                const originalText = this.innerHTML;
                this.classList.add('loading');
                this.disabled = true;
                
                // Add loading spinner
                const spinner = document.createElement('span');
                spinner.classList.add('spinner');
                this.appendChild(spinner);
                
                // Simulate loading state
                setTimeout(() => {
                    this.classList.remove('loading');
                    this.disabled = false;
                    this.innerHTML = originalText;
                }, 2000);
            }
        });
    });
    
    // Add parallax effect to hero section
    const heroSection = document.querySelector('.hero-section');
    if (heroSection) {
        window.addEventListener('scroll', () => {
            const scrolled = window.pageYOffset;
            const rate = scrolled * 0.5;
            
            heroSection.style.backgroundPosition = `center ${rate}px`;
            
            // Parallax for hero content
            const heroContent = heroSection.querySelectorAll('h1, p, .btn');
            heroContent.forEach(element => {
                element.style.transform = `translateY(${scrolled * 0.2}px)`;
            });
        });
    }
    
    // Add smooth transition for images
    const images = document.querySelectorAll('img');
    images.forEach(img => {
        img.addEventListener('load', function() {
            img.classList.add('img-loaded');
        });
    });
});