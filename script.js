document.addEventListener('DOMContentLoaded', () => {
    // Mobile Menu Toggle
    const mobileMenuBtn = document.getElementById('mobile-menu-btn');
    const mobileMenu = document.getElementById('mobile-menu');
    const mobileNavLinks = document.querySelectorAll('.mobile-nav-link');

    if (mobileMenuBtn && mobileMenu) {
        mobileMenuBtn.addEventListener('click', () => {
            mobileMenu.classList.toggle('active');

            // Update icon based on state (optional, but good UX)
            const isOpen = mobileMenu.classList.contains('active');
            mobileMenuBtn.innerHTML = isOpen
                ? '<svg width="24" height="24" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M6 18L18 6M6 6l12 12"/></svg>'
                : '<svg width="24" height="24" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 6h16M4 12h16M4 18h16"/></svg>';
        });

        // Close menu when a link is clicked
        mobileNavLinks.forEach(link => {
            link.addEventListener('click', () => {
                mobileMenu.classList.remove('active');
                mobileMenuBtn.innerHTML = '<svg width="24" height="24" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 6h16M4 12h16M4 18h16"/></svg>';
            });
        });
    }

    // Header Scroll Effect
    const header = document.getElementById('header');

    window.addEventListener('scroll', () => {
        if (window.scrollY > 50) {
            header.classList.add('scrolled');
        } else {
            header.classList.remove('scrolled');
        }
    });

    // Plan Selection Logic
    window.selectPlan = (planValue) => {
        const planSelect = document.getElementById('preferredPlan');
        const registerSection = document.getElementById('register');

        if (planSelect) {
            planSelect.value = planValue;
        }

        if (registerSection) {
            registerSection.scrollIntoView({ behavior: 'smooth' });
        }
    };

    // Form Submission (Mock)
    const registerForm = document.getElementById('register-form');

    if (registerForm) {
        registerForm.addEventListener('submit', (e) => {
            e.preventDefault();

            // Get form data
            const formData = new FormData(registerForm);
            const data = Object.fromEntries(formData.entries());

            console.log('Form Submitted:', data);

            // Show success message (simple alert for now, or replace form content)
            const container = registerForm.parentElement;
            container.innerHTML = `
                <div class="text-center p-12" style="background-color: hsl(var(--card)); border: 1px solid hsl(var(--border)); border-radius: var(--radius);">
                    <div style="width: 4rem; height: 4rem; background-color: hsl(var(--primary) / 0.1); border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 1.5rem;">
                        <svg width="32" height="32" style="color: hsl(var(--primary));" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                        </svg>
                    </div>
                    <h3 style="font-family: var(--font-serif); font-size: 1.5rem; margin-bottom: 0.5rem;">Thank You</h3>
                    <p style="color: hsl(var(--muted-foreground));">We've received your inquiry and will be in touch within 24 hours.</p>
                </div>
            `;
        });
    }

    // Hero Slider Logic
    const initHeroSlider = () => {
        const slides = document.querySelectorAll('.slide');
        const track = document.getElementById('sliderTrack');
        const dots = document.querySelectorAll('.indicator');
        const prevBtn = document.getElementById('prevSlide');
        const nextBtn = document.getElementById('nextSlide');

        if (!slides.length || !track) return;

        let currentSlide = 0;
        const totalSlides = slides.length;
        let slideInterval;

        const updateSlidePosition = (index) => {
            // Update dots
            dots.forEach(dot => dot.classList.remove('active'));

            // Handle wrapping
            if (index >= totalSlides) currentSlide = 0;
            else if (index < 0) currentSlide = totalSlides - 1;
            else currentSlide = index;

            // Move track
            track.style.transform = `translateX(-${currentSlide * 100}%)`;

            // Update active dot
            dots[currentSlide].classList.add('active');
        };

        const nextSlide = () => {
            updateSlidePosition(currentSlide + 1);
        };

        const prevSlide = () => {
            updateSlidePosition(currentSlide - 1);
        };

        // Event Listeners
        if (nextBtn) nextBtn.addEventListener('click', () => {
            nextSlide();
            resetTimer();
        });

        if (prevBtn) prevBtn.addEventListener('click', () => {
            prevSlide();
            resetTimer();
        });

        dots.forEach((dot, index) => {
            dot.addEventListener('click', () => {
                updateSlidePosition(index);
                resetTimer();
            });
        });

        // Initialize first slide as active
        dots[0].classList.add('active');

        // Auto Play
        const startTimer = () => {
            slideInterval = setInterval(nextSlide, 3000);
        };

        const resetTimer = () => {
            clearInterval(slideInterval);
            startTimer();
        };

        startTimer();
    };

    initHeroSlider();
});

// Scroll Animations
const observerOptions = {
    threshold: 0.15,
    rootMargin: "0px 0px -50px 0px"
};

const observer = new IntersectionObserver((entries) => {
    entries.forEach(entry => {
        if (entry.isIntersecting) {
            entry.target.classList.add('is-visible');
            observer.unobserve(entry.target); // Only animate once
        }
    });
}, observerOptions);

// Observe all elements with .reveal-on-scroll
document.querySelectorAll('.reveal-on-scroll').forEach(element => {
    observer.observe(element);
});

// Also observe gallery items for the zoom effect
document.querySelectorAll('.gallery-item').forEach((item, index) => {
    item.classList.add('reveal-on-scroll');
    item.style.transitionDelay = `${index * 100}ms`; // Staggered delay
    observer.observe(item);
});

// Smooth Page Transition Effect
function initPageTransition() {
    // Add fade-in effect on page load
    document.body.style.opacity = '0';
    document.body.style.transition = 'opacity 0.4s ease';

    window.addEventListener('load', () => {
        document.body.style.opacity = '1';
    });

    // Handle navigation links with smooth fade
    const navLinks = document.querySelectorAll('a[href="gallery.html"], a[href="index.html"]');

    navLinks.forEach(link => {
        link.addEventListener('click', (e) => {
            e.preventDefault();
            const targetUrl = link.getAttribute('href');

            // Fade out current page
            document.body.style.opacity = '0';

            // Navigate after fade
            setTimeout(() => {
                window.location.href = targetUrl;
            }, 400);
        });
    });
}

// Initialize on page load
initPageTransition();
