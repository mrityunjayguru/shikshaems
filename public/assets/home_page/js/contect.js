document.addEventListener('DOMContentLoaded', () => {
    // GSAP Animations
    gsap.registerPlugin(ScrollTrigger);

    // Hero Section Animation
    gsap.to('.contact-hero', {
        opacity: 1,
        y: 0,
        duration: 1,
        ease: 'power3.out',
        delay: 0.2
    });

    // Info Section Animation
    gsap.to('.contact-info', {
        opacity: 1,
        y: 0,
        duration: 1,
        ease: 'power3.out',
        delay: 0.4
    });

    // Form Wrapper Animation
    gsap.to('.contact-form-wrapper', {
        opacity: 1,
        y: 0,
        duration: 1,
        ease: 'power3.out',
        delay: 0.6
    });

    // Stagger animation for contact details
    gsap.from('.contact-detail-group', {
        opacity: 0,
        y: 20,
        duration: 0.8,
        stagger: 0.15,
        ease: 'power2.out',
        delay: 0.8
    });

    // Toggle fields based on subject
    const subjectSelect = document.getElementById('subject');
    const generalFields = document.getElementById('generalFields');
    const supportFields = document.getElementById('supportFields');

    function updateFields() {
        if (subjectSelect.value === 'support') {
            generalFields.style.display = 'none';
            supportFields.style.display = 'block';
        } else {
            generalFields.style.display = 'block';
            supportFields.style.display = 'none';
        }
    }

    // Handle URL parameters
    const urlParams = new URLSearchParams(window.location.search);
    const typeParam = urlParams.get('type');
    if (typeParam === 'support') {
        subjectSelect.value = 'support';
    }

    // Initial update
    updateFields();

    subjectSelect.addEventListener('change', updateFields);

    // Form Validation
    const submitBtn = document.querySelector('.contact-submit-btn');

    submitBtn.addEventListener('click', (e) => {
        e.preventDefault();
        let isValid = true;

        // Reset error messages
        document.querySelectorAll('.error-message').forEach(el => el.textContent = '');

        const currentSubject = subjectSelect.value;

        if (currentSubject === 'general') {
            const fullName = document.getElementById('fullName');
            const email = document.getElementById('email');
            const phone = document.getElementById('phone');

            if (!fullName.value.trim()) {
                document.getElementById('fullNameError').textContent = 'Required';
                isValid = false;
            }
            if (!email.value.trim()) {
                document.getElementById('emailError').textContent = 'Required';
                isValid = false;
            } else if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email.value)) {
                document.getElementById('emailError').textContent = 'Invalid email';
                isValid = false;
            }
            if (!phone.value.trim()) {
                document.getElementById('phoneError').textContent = 'Required';
                isValid = false;
            }
        } else if (currentSubject === 'support') {
            const schoolName = document.getElementById('schoolName');
            const schoolEmail = document.getElementById('schoolEmail');
            const message = document.getElementById('message');

            if (!schoolName.value.trim()) {
                document.getElementById('schoolNameError').textContent = 'Required';
                isValid = false;
            }
            if (!schoolEmail.value.trim()) {
                document.getElementById('schoolEmailError').textContent = 'Required';
                isValid = false;
            } else if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(schoolEmail.value)) {
                document.getElementById('schoolEmailError').textContent = 'Invalid email';
                isValid = false;
            }
            if (!message.value.trim()) {
                document.getElementById('messageError').textContent = 'Required';
                isValid = false;
            }
        }

        const terms = document.getElementById('terms');
        if (!terms.checked) {
            document.getElementById('termsError').textContent = 'You must agree to terms';
            isValid = false;
        }

        if (isValid) {
            // Success - would typically submit form here
            const originalText = submitBtn.innerHTML;
            submitBtn.innerHTML = 'Submitting...';
            submitBtn.disabled = true;

            setTimeout(() => {
                alert('Thank you! Your message has been sent.');
                submitBtn.innerHTML = originalText;
                submitBtn.disabled = false;
                // Optional: reset form
                // document.querySelector('.contact-form').reset();
            }, 1000);
        }
    });
});
