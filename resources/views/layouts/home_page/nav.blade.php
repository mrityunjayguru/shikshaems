  <!-- Navigation -->
  <div class="navigation">
      <button class="contact-btn_nav">
          <a href="{{ route('support') }}">
              Contact Us
          </a>
      </button>
      <button class="school-btn_nav">
          <a href="{{ route('login') }}">
              School login
          </a>
      </button>
  </div>
  <!-- Navbar -->
  <nav class="navbar">
      <a href="{{ url('/') }}" class="navbar__logo">
          <img src="{{ asset('assets/icons/home-icons/logo-icon.svg') }}"
              alt="ShikshaEMS Icon" class="navbar__logo-icon" />
      </a>
      <div class="navbar__menu-container" id="navMenuContainer">
          <ul class="navbar__menu">
              <li class="navbar__menu-item"><a href="#">Why ShikshaEMS</a></li>
              <li class="navbar__menu-item _has-dropdown" id="featuresMenuItem">
                  <a href="#">Features</a>
                  <div class="dropdown-menu">
                      <div class="dropdown-menu__content">
                          <div class="dropdown-menu__list">
                              <a href="{{ route('features.page') }}" class="dropdown-item">
                                  <div class="dropdown-item__icon">
                                      <img src="{{ asset('assets/icons/home-icons/settings-user.svg') }}"
                                          alt="ERP Features">
                                  </div>
                                  <div class="dropdown-item__text">
                                      <h4>ERP Features</h4>
                                      <p>Manage your institute effortlessly.</p>
                                  </div>
                              </a>
                              <a href="#" class="dropdown-item">
                                  <div class="dropdown-item__icon">
                                      <img src="{{ asset('assets/icons/home-icons/man.svg') }}"
                                          alt="Parent & Student App">
                                  </div>
                                  <div class="dropdown-item__text">
                                      <h4>Parent & Student App</h4>
                                      <p>Stay informed. Stay connected.</p>
                                  </div>
                              </a>
                              <a href="#" class="dropdown-item">
                                  <div class="dropdown-item__icon">
                                      <img src="{{ asset('assets/icons/home-icons/school.svg') }}"
                                          alt="Teacher & Staff App">
                                  </div>
                                  <div class="dropdown-item__text">
                                      <h4>Teacher & Staff App</h4>
                                      <p>Work smarter, not harder.</p>
                                  </div>
                              </a>
                          </div>
                          <div class="dropdown-menu__image">
                              <img src="{{ asset('assets/icons/home-icons/about.svg') }}" alt="Features Preview"
                                  class="dropdown-image-placeholder">
                          </div>
                      </div>
                  </div>
              </li>
              <li class="navbar__menu-item"><a href="#">Pricing</a></li>
              {{-- <li class="navbar__menu-item _has-dropdown"><a href="#">Resources</a></li> --}}
              <li class="navbar__menu-item"><a href="{{ route('support') }}">Support</a></li>
          </ul>
          <button class="navbar__cta">
              <a href="{{route('schedule.demo')}}">
                  Schedule a Demo
              </a>
          </button>
      </div>
      <button class="navbar__hamburger" id="hamburgerBtn" aria-label="Toggle menu">
          <span></span>
          <span></span>
          <span></span>
      </button>
  </nav>
