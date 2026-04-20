@extends('layouts.home_page.master')
@section('content') 
    <section class="contact-page">
        <div class="contact-hero">
            <h1 class="contact-hero__title">Schedule a Demo</h1>
        </div>

        <div class="contact-container">
            <div class="contact-info">
                {{-- <div class="badge-how">One Platform. Every Need.</div> --}}
                <h2 class="contact-info__title">We'd love to hear from you</h2>
                <p class="contact-info__description">Discover how ShikshaEMS streamlines your entire school management.</p>

                {{-- <div class="contact-details">
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
                </div> --}}
            </div>

            <div class="contact-form-wrapper">
                <h2 class="contact-form__title">Get In Touch</h2>
                {{-- <form class="contact-form">
                    
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
                                <label for="fullName" class="form-label">Your Roll</label>
                                <span class="error-message" id="fullNameError"></span>
                            </div>
                            <input type="text" id="fullName" class="form-control" />
                        </div>
                        <div class="form-group">
                            <div class="form-label-row">
                                <label for="phone" class="form-label">Phone</label>
                                <span class="error-message" id="phoneError"></span>
                            </div>
                            <input type="tel" id="phone" class="form-control" />
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
                                <label for="date" class="form-label">Date</label>
                                <span class="error-message" id="dateError"></span>
                            </div>
                            <input type="date" id="date" class="form-control" />
                        </div>                    
                        <div class="form-group">
                            <div class="form-label-row">
                                <label for="time" class="form-label">Time</label>
                                <span class="error-message" id="timeError"></span>
                            </div>
                            <input type="time" id="time" class="form-control" />
                        </div>                    
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
                </form> --}}
                <form class="contact__form">
                    <div class="form-group">
                        <label for="name">Full name<span class="required">*</span></label>
                        <input type="text" id="name" required>
                    </div>
                    <div class="form-group">
                        <label for="role">Your role<span class="required">*</span></label>
                        <input type="text" id="role" required>
                    </div>
                    <div class="form-group">
                        <label for="phone">Phone<span class="required">*</span></label>
                        <input type="tel" id="phone" required>
                    </div>
                    <div class="form-group">
                        <label for="email">Email<span class="required">*</span></label>
                        <input type="email" id="email" required>
                    </div>
                    <div class="form-group">
                        <label for="date">Date<span class="required">*</span></label>
                        <input type="date" id="date" required>
                    </div>
                    <div class="form-group">
                        <label for="time">Time<span class="required">*</span></label>
                        <input type="time" id="time" required>
                    </div>
                    <button type="submit" class="btn-request">
                        Request a Demo
                        <img src="{{ asset('assets/icons/home-icons/Frame 1.svg') }}" alt="Request a Demo" />
                    </button>
                </form>
            </div>
        </div>
    </section><hr>
@endsection
