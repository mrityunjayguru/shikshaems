@extends('layouts.home_page.master')
@section('content') 
    <section class="contact-page">
        <div class="contact-hero">
            <h1 class="contact-hero__title">Contact</h1>
        </div>

        <div class="contact-container">
            <div class="contact-info">
                <div class="badge-how">One Platform. Every Need.</div>
                <h2 class="contact-info__title">We'd love to hear from you</h2>
                <p class="contact-info__description">Please complete the contact form below and someone will be in touch
                    as soon as possible.</p>

                <div class="contact-details">
                    <div class="contact-detail-group">
                        <h3 class="contact-detail-group__title-blue">Locations</h3>
                        <p class="contact-detail-group__text-fade">Delhi NCR | Mumbai | Ranchi</p>
                    </div>

                    <div class="contact-detail-group">
                        <h3 class="contact-detail-group__title-dark">Head Office</h3>
                        <p class="contact-detail-group__text">191 Chicago, California - 93721</p>
                    </div>

                    <div class="contact-detail-group">
                        <h3 class="contact-detail-group__title-dark">Email</h3>
                        <p class="contact-detail-group__text"><a href="mailto:hello@shikshaEMS.com"
                                class="contact-link">hello@shikshaEMS.com</a></p>
                    </div>

                    <div class="contact-detail-group">
                        <h3 class="contact-detail-group__title-dark">Phone Number</h3>
                        <p class="contact-detail-group__text"><a href="tel:+919876543210" class="contact-link">+91 9876
                                543 210</a></p>
                    </div>
                </div>
            </div>

            <div class="contact-form-wrapper">
                <h2 class="contact-form__title">Get In Touch</h2>
                <form class="contact-form">
                    <div class="form-group">
                        <div class="form-label-row">
                            <label for="subject" class="form-label">Subject of enquiry</label>
                            <span class="error-message" id="subjectError"></span>
                        </div>
                        <div class="custom-select-wrapper">
                            <select id="subject" class="form-control text-fade custom-select">
                                <option value="general" selected>General</option>
                                <option value="support">Support</option>
                            </select>
                            <div class="custom-select-icon">
                                <svg width="12" height="8" viewBox="0 0 12 8" fill="none"
                                    xmlns="http://www.w3.org/2000/svg">
                                    <path d="M1 1.5L6 6.5L11 1.5" stroke="#4A8BF5" stroke-width="1.5" stroke-linecap="round"
                                        stroke-linejoin="round" />
                                </svg>
                            </div>
                        </div>
                    </div>

                    <div id="generalFields">
                        <div class="form-group">
                            <div class="form-label-row">
                                <label for="fullName" class="form-label">Full name</label>
                                <span class="error-message" id="fullNameError"></span>
                            </div>
                            <input type="text" id="fullName" class="form-control" />
                        </div>

                        <div class="form-group">
                            <div class="form-label-row">
                                <label for="email" class="form-label">Email</label>
                                <span class="error-message" id="emailError"></span>
                            </div>
                            <input type="email" id="email" class="form-control" />
                        </div>

                        <div class="form-group">
                            <div class="form-label-row">
                                <label for="phone" class="form-label">Phone</label>
                                <span class="error-message" id="phoneError"></span>
                            </div>
                            <input type="tel" id="phone" class="form-control" />
                        </div>
                    </div>

                    <div id="supportFields" style="display: none;">
                        <div class="form-group">
                            <div class="form-label-row">
                                <label for="schoolName" class="form-label">School Name</label>
                                <span class="error-message" id="schoolNameError"></span>
                            </div>
                            <input type="text" id="schoolName" class="form-control" />
                        </div>

                        <div class="form-group">
                            <div class="form-label-row">
                                <label for="schoolEmail" class="form-label">School Email</label>
                                <span class="error-message" id="schoolEmailError"></span>
                            </div>
                            <input type="email" id="schoolEmail" class="form-control" />
                        </div>

                        <div class="form-group">
                            <div class="form-label-row">
                                <label for="message" class="form-label">Message</label>
                                <span class="error-message" id="messageError"></span>
                            </div>
                            <textarea id="message" class="form-control form-textarea"></textarea>
                        </div>
                    </div>

                    <div class="form-group checkbox-group">
                        <div class="checkbox-container">
                            <input type="checkbox" id="terms" class="form-checkbox" />
                            <label for="terms" class="checkbox-label">I agree to <a href="#"
                                    class="terms-link text-blue">terms and conditions</a>.</label>
                        </div>
                        <span class="error-message" id="termsError"></span>
                    </div>

                    <button type="button" class="btn btn--secondary contact-submit-btn">
                        Submit
                        <span class="submit-icon-wrapper">
                            <svg width="14" height="14" viewBox="0 0 14 14" fill="none"
                                xmlns="http://www.w3.org/2000/svg">
                                <path d="M4 10L10 4M10 4V9.5M10 4H4.5" stroke="#FF672B" stroke-width="2"
                                    stroke-linecap="round" stroke-linejoin="round" />
                            </svg>
                        </span>
                    </button>
                </form>
            </div>
        </div>
    </section><hr>
@endsection
