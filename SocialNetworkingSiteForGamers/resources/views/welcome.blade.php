<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <title>ArcadeUnion – społeczność graczy</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="{{ asset('home.css') }}" rel="stylesheet">
    <style>
         body {
            background: #f4f4f4 !important;
        }
        .hero-section {
           
            color: black;
            padding: 60px 0 40px 0;
            text-align: center;
        }
        .hero-logo {
            width: 100px;
            margin-bottom: 1rem;
        }
        .hero-title {
            font-size: 2.8rem;
            font-weight: bold;
            color: #4f8cff;
            margin-bottom: 1rem;
            letter-spacing: 2px;
        }
        .hero-desc {
            font-size: 1.2rem;
           
            margin-bottom: 2rem;
        }
        .hero-btns .btn {
            min-width: 140px;
            margin: 0 10px 10px 0;
            font-size: 1.1rem;
        }
        .features-section {
            background: #f4f4f4;
            color: #23272f;
            padding: 50px 0 30px 0;
        }
        .feature-card {
            background: #fff;
            border-radius: 14px;
            box-shadow: 0 4px 24px rgba(0,0,0,0.10);
            padding: 2rem 1.5rem;
            margin-bottom: 2rem;
            min-height: 320px;
            transition: box-shadow 0.2s;
        }
        .feature-card:hover {
            box-shadow: 0 8px 32px rgba(79,140,255,0.13);
        }
        .feature-icon {
            font-size: 2.5rem;
            color: #4f8cff;
            margin-bottom: 1rem;
        }
        .about-section {
            background: #23272f;
            color: #fff;
            padding: 40px 0 30px 0;
        }
        .about-title {
            color: #4f8cff;
            font-size: 2rem;
            font-weight: bold;
            margin-bottom: 1rem;
        }
        .about-desc {
            font-size: 1.1rem;
            color: #b0b8c1;
        }
        .footer {
            background: #181b20;
            color: #b0b8c1;
            padding: 20px 0;
            text-align: center;
            font-size: 1rem;
        }
    </style>
</head>
<body>
@if (Auth::check())
    <script>window.location = "{{ route('home') }}";</script>
@endif


<section class="hero-section">
    <img src="{{ asset('IMG/LogoArcadeUnionDefault.png') }}" alt="ArcadeUnion Logo" class="hero-logo">
    <div class="hero-title">ArcadeUnion</div>
    <div class="hero-desc">
        Projekt poświęcony od gracza dla graczy.<br>
        Znajdź kompanów do wspólnej gry, twórz drużyny, planuj mecze i rozwijaj swoją pasję!
    </div>
    <div class="hero-btns mb-3">
        <a href="{{ route('login') }}" class="btn btn-primary arcade-btn">Zaloguj się</a>
        <a href="{{ route('register') }}" class="btn btn-outline-primary arcade-btn">Zarejestruj się</a>
    </div>
</section>


<section class="about-section">
    <div class="container">
        <div class="about-title text-center">O aplikacji</div>
        <div class="about-desc mx-auto" style="max-width: 700px; text-align: justify;">
            <strong>ArcadeUnion</strong> to nowoczesna platforma społecznościowa stworzona przez graczy dla graczy. Umożliwia łatwe wyszukiwanie osób do wspólnej gry, tworzenie drużyn oraz planowanie meczów. 
            <br><br>
            W aplikacji możesz publikować trzy rodzaje postów: dyskusje, ogłoszenia o wspólnej grze oraz rekrutacje do drużyn. Ogłoszenia pojawiają się na stronie głównej, gdzie inni użytkownicy mogą zgłaszać chęć udziału w wydarzeniu. Autor posta ma możliwość akceptowania lub odrzucania zgłoszeń po weryfikacji profilu gracza.
            <br><br>
            Po zaakceptowaniu zgłoszenia, mecz pojawia się w kalendarzu z opcją ustalenia wyniku. Jako lider drużyny możesz zarządzać zespołem, prowadzić rekrutację i organizować wydarzenia dla całej grupy. 
            <br><br>
            ArcadeUnion oferuje także rozbudowane statystyki rozegranych meczów oraz procentowe zestawienie zwycięstw każdego gracza i drużyny. Dołącz do społeczności i rozwijaj swoją pasję do gier!
        </div>
    </div>
</section>


<section class="features-section">
    <div class="container">
        <div class="row text-center">
            <div class="col-md-4">
                <div class="feature-card h-100">
                    <div class="feature-icon"><i class="bi bi-people"></i></div>
                    <h5>Wyszukiwanie graczy</h5>
                    <p>Dodawaj ogłoszenia i znajdź osoby do wspólnej gry w ulubione tytuły. Przeglądaj profile i wybieraj z kim chcesz zagrać.</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="feature-card h-100">
                    <div class="feature-icon"><i class="bi bi-calendar-event"></i></div>
                    <h5>Kalendarz wydarzeń</h5>
                    <p>Twórz i dołączaj do wydarzeń. Akceptowane mecze pojawiają się w kalendarzu, gdzie możesz śledzić terminy i wyniki spotkań.</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="feature-card h-100">
                    <div class="feature-icon"><i class="bi bi-trophy"></i></div>
                    <h5>Drużyny i statystyki</h5>
                    <p>Twórz drużyny, zarządzaj rekrutacją i spotkaniami. Sprawdzaj statystyki meczów oraz procent zwycięstw swoich i zespołu.</p>
                </div>
            </div>
        </div>
    </div>
</section>


<footer class="footer">
    &copy; {{ date('Y') }} ArcadeUnion – Twoja społeczność graczy
</footer>


<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
</body>
</html>