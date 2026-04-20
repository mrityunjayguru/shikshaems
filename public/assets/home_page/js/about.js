document.addEventListener('DOMContentLoaded', () => {
    // GSAP Animations
    gsap.registerPlugin(ScrollTrigger);

    // Hero Section Animation
    gsap.to('.about-hero', {
        opacity: 1,
        y: 0,
        duration: 1,
        ease: 'power3.out',
        delay: 0.2
    });

    // Header Animations
    document.querySelectorAll('.about-header').forEach(header => {
        gsap.to(header, {
            opacity: 1,
            y: 0,
            duration: 1,
            ease: 'power3.out',
            scrollTrigger: {
                trigger: header,
                start: 'top 85%',
                toggleActions: 'play none none none'
            }
        });
    });

    // Stagger animation for feature cards in each grid
    document.querySelectorAll('.features-grid').forEach(grid => {
        gsap.to(grid.querySelectorAll('.feature-card-item'), {
            opacity: 1,
            y: 0,
            duration: 0.8,
            stagger: 0.1,
            ease: 'power2.out',
            scrollTrigger: {
                trigger: grid,
                start: 'top 85%',
                toggleActions: 'play none none none'
            }
        });
    });
});
