<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>Track Vehicle</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <style>
        * {
            box-sizing: border-box;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        body {
            margin: 0;
            background: #f4f6f9;
            height: 100vh;
        }

        .track-container {
            display: flex;
            height: 100vh;
        }

        /* LEFT IMAGE SECTION */
        .track-image {
            flex: 1;
            background: linear-gradient(rgba(0, 0, 0, 0.4),
                    rgba(0, 0, 0, 0.4)),
                url("{{ asset('assets/landing_page_images/heroImg.png') }}");
            background-size: cover;
            background-position: center;
            color: #fff;
            display: flex;
            align-items: center;
            justify-content: center;
            text-align: center;
            padding: 40px;
        }

        .track-image h1 {
            font-size: 36px;
            margin-bottom: 10px;
        }

        .track-image p {
            font-size: 16px;
            opacity: 0.9;
        }

        /* RIGHT FORM SECTION */
        .track-form {
            flex: 1;
            background: #ffffff;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 40px;
        }

        .form-box {
            width: 100%;
            max-width: 380px;
        }

        .form-box img {
            display: block;
            margin: 0 auto 20px;
            max-width: 160px;
        }

        .form-box h3 {
            text-align: center;
            margin-bottom: 5px;
            color: #333;
        }

        .form-box span {
            display: block;
            text-align: center;
            color: #666;
            margin-bottom: 25px;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-group input {
            width: 100%;
            padding: 14px;
            font-size: 15px;
            border: 1px solid #ccc;
            border-radius: 6px;
            outline: none;
        }

        .form-group input:focus {
            border-color: #007bff;
        }

        .track-btn button {
            width: 100%;
            padding: 14px;
            background: #007bff;
            border: none;
            color: #fff;
            font-size: 16px;
            border-radius: 6px;
            cursor: pointer;
            transition: background 0.3s;
        }

        .track-btn button:hover {
            background: #0056b3;
        }

        .validation-div {
            color: red;
            font-size: 13px;
            margin-top: 5px;
        }

        /* RESPONSIVE */
        @media (max-width: 768px) {
            .track-container {
                flex-direction: column;
            }

            .track-image {
                height: 40vh;
            }
        }
    </style>
</head>

<body>

    <div class="track-container">

        <!-- LEFT IMAGE -->
        <div class="track-image">
            <div>
                <h1>Track Your Vehicle</h1>
                <p>Enter your tracking code to view live vehicle location</p>
            </div>
        </div>

        <!-- RIGHT FORM -->
        <div class="track-form">
            <div class="form-box">
                <img src="{{ asset('assets/landing_page_images/sems-logo-main.svg') }}" alt="Logo">

                <h3>Enter Tracking Code</h3>
                <span>Track your vehicle in real-time</span>

                <form action="javascript:void(0)">
                    <div class="form-group">
                        <input type="text" id="code" placeholder="Enter tracking code" required>
                        <div class="validation-div" id="val-code"></div>
                    </div>

                    <div class="track-btn">
                        <button type="submit" onclick="ticketValidate()">TRACK VEHICLE</button>
                    </div>
                </form>
            </div>
        </div>

    </div>

    <script>
        function ticketValidate() {
            var data = new FormData();
            data.append('code', $('#code').val());

            var token = "{{ csrf_token() }}";

            $.ajax({
                url: "{{ route('ajax.code.validate') }}",
                type: "POST",
                data: data,
                processData: false,
                contentType: false,
                headers: {
                    'X-CSRF-TOKEN': token
                },
                success: function(response) {
                    console.log(response);

                    if (response.code == '200') {
                        window.location.href = "{{ url('track') }}/" + $('#code').val();
                    } else if (response.code == '103') {
                        $('#val-code').text('Invalid tracking code');
                    } else {
                        $('#val-code').text('Something went wrong. Please try again.');
                    }
                },
                error: function(xhr) {
                    console.log(xhr.responseText);
                }
            });
        }
    </script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"
        integrity="sha512-v2CJ7UaYy4JwqLDIrZUI/4hqeoQieOmAZNXBeQyjo21dadnwR+8ZaIJVT8EE2iyI61OV8e6M8PP2/4hpQINQ/g=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>
</body>

</html>
