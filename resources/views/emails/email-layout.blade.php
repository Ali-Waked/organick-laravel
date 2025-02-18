<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>{{ $title }}</title>
</head>

<body style="background-color: #eeeeee; font-family: Arial,sans-serif">
    <div class="container"
        style="max-width: 600px; margin: auto; background-color: #fff; margin-top:20px; box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.1);">

        <!-- Header -->
        <header
            style="background-color: #7eb693; color: #fff; display: flex; align-items: center; justify-content: center; padding: 20px;">
            <img src="{{ asset('logo_white.svg') }}" width="30px" alt="logo" />
            <span style="font-weight: bold; font-size: 30px; margin-left: 12px;">Organcik</span>
        </header>

        <!-- Main Content -->
        <main style="padding: 16px 24px;line-height: 1.8">
            {{ $slot }}
        </main>

        <!-- Footer -->
        <footer style="background-color: #274c5b; color: #fff; text-align: center; padding: 14px; font-size: 14px;">
            <span style='margin-bottom: 6px; display: inline-block;'> &copy; {{ Carbon\Carbon::now()->year }}
                Organcik. All rights
                reserved.</span>
            <br>
            <small>1234 Example St, Gaza, Palestine | Contact: {{ Config::get('mail.from.address') }}</small>
        </footer>
    </div>
</body>

</html>
