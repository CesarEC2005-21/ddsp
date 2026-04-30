/**
 * Sanchez Pharma - Main Interactivity
 */

document.addEventListener('DOMContentLoaded', () => {
    // 1. Sticky Navbar Logic
    const nav = document.querySelector('nav');
    const handleScroll = () => {
        if (window.scrollY > 50) {
            nav.classList.add('scrolled');
        } else {
            nav.classList.remove('scrolled');
        }
    };
    window.addEventListener('scroll', handleScroll);
    handleScroll(); // Initial check

    // 2. Intersection Observer for Entrance Animations
    const revealElements = document.querySelectorAll('.reveal');
    const revealObserver = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add('active');
            }
        });
    }, {
        threshold: 0.1
    });

    revealElements.forEach(el => revealObserver.observe(el));

    // 3. Stats Counter Animation
    const stats = document.querySelectorAll('.stat-item h3');
    const animateStats = (el) => {
        const target = +el.getAttribute('data-target');
        const count = +el.innerText;
        const speed = 200;
        const increment = target / speed;

        if (count < target) {
            el.innerText = Math.ceil(count + increment);
            setTimeout(() => animateStats(el), 1);
        } else {
            el.innerText = target + '+';
        }
    };

    const statsObserver = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                animateStats(entry.target);
                statsObserver.unobserve(entry.target);
            }
        });
    }, { threshold: 1 });

    stats.forEach(stat => statsObserver.observe(stat));

    // 4. Smooth Scroll for Navigation Links
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function (e) {
            e.preventDefault();
            const targetId = this.getAttribute('href');
            const target = document.querySelector(targetId);
            if (target) {
                window.scrollTo({
                    top: target.offsetTop - 80,
                    behavior: 'smooth'
                });
            }
        });
    });
});
