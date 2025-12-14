<!DOCTYPE html>
<html lang="lt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Knygų rekomendacijos</title>

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&family=Playfair+Display:wght@700&display=swap" rel="stylesheet">

    <!-- Heroicons SVG CDN -->
    <script src="https://unpkg.com/feather-icons"></script>

    <style>
        /* --- General --- */
        * { box-sizing: border-box; margin: 0; padding: 0; font-family: 'Roboto', sans-serif; }
        body { display: flex; flex-direction: column; min-height: 100vh; }
        a { text-decoration: none; color: inherit; }

        /* --- Header --- */
        header {
            background-color: #4A90E2;
            color: white;
            padding: 1rem 2rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .logo { font-family: 'Playfair Display', serif; font-size: 1.5rem; }
        nav ul { display: flex; list-style: none; gap: 1rem; }
        nav li { transition: transform 0.3s; }
        nav li:hover { transform: scale(1.1); }

        /* --- Hamburger Menu --- */
        .hamburger { display: none; cursor: pointer; }
        .hamburger div { width: 25px; height: 3px; background-color: white; margin: 5px 0; transition: 0.3s; }

        /* --- Content --- */
        main { flex: 1; padding: 2rem; background-color: #F0F4F8; }
        .books { display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 1rem; }
        .book-card {
            background: white;
            padding: 1rem;
            border-radius: 8px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
            transition: transform 0.3s;
        }
        .book-card:hover { transform: translateY(-5px); }
        .book-card img { width: 100%; height: auto; border-radius: 5px; margin-bottom: 0.5rem; }

        /* --- Form --- */
        form { margin-top: 2rem; background: #fff; padding: 1rem; border-radius: 8px; box-shadow: 0 2px 5px rgba(0,0,0,0.1); }
        form input, form textarea, form select, form button {
            width: 100%; margin-bottom: 1rem; padding: 0.5rem; border-radius: 5px; border: 1px solid #ccc; font-size: 1rem;
        }
        form button {
            background-color: #4A90E2; color: white; border: none; cursor: pointer; transition: background 0.3s;
        }
        form button:hover { background-color: #357ABD; }

        /* --- Footer --- */
        footer {
            background-color: #333;
            color: white;
            padding: 2rem;
            display: flex;
            justify-content: space-between;
        }
        footer div { flex: 1; }
        footer h4 { margin-bottom: 1rem; }
        footer a { color: #4A90E2; display: block; margin-bottom: 0.5rem; }

        /* --- Modal --- */
        .modal { display: none; position: fixed; top:0; left:0; width:100%; height:100%; background: rgba(0,0,0,0.5); justify-content: center; align-items: center; }
        .modal-content { background:white; padding:2rem; border-radius:8px; max-width:400px; width:90%; }
        .close { cursor:pointer; float:right; font-size:1.2rem; }

        /* --- Responsive --- */
        @media (max-width:768px){
            nav ul { display: none; flex-direction: column; background: #4A90E2; position: absolute; top: 60px; right: 0; width: 200px; padding: 1rem; }
            nav ul.active { display: flex; }
            .hamburger { display: block; }
        }
    </style>
</head>
<body>

<header>
    <div class="logo">Knygos</div>
    <nav>
        <ul id="menu">
            <li><a href="index.html">Pagrindinis</a></li>
            <li><a href="knygos.html">Knygos</a></li>
            <li><a href="rekomendacijos.html">Rekomendacijos</a></li>
            <li><a href="profilis.html"><i data-feather="user"></i>Profilis</a></li>
        </ul>
        <div class="hamburger" id="hamburger">
            <i data-feather="menu"></i>
        </div>
    </nav>
</header>

<main>
    <!-- PRISIJUNGIMO FORMA -->
    <h2>Prisijungimas</h2>
    <form id="loginForm">
        <input type="email" id="email" placeholder="El. paštas" required>
        <input type="password" id="password" placeholder="Slaptažodis" required>
        <button type="submit">Prisijungti</button>
    </form>

    <h2>Knygų sąrašas</h2>
    <div class="books" id="books">
        <!-- Kortelės bus įkelta per JS iš API -->
    </div>

    

    <h2>Pridėti rekomendaciją</h2>
    <form id="rekomForm">
        <select id="knygaSelect" required></select>
        <input type="text" id="naudotojas" placeholder="Jūsų vardas" required>
        <textarea id="komentaras" placeholder="Komentaras" required></textarea>
        <input type="number" id="ivertinimas" placeholder="Įvertinimas (1-5)" min="1" max="5" required>
        <button type="submit">Siųsti</button>
    </form>
</main>

<footer>
    <div>
        <h4>Kontaktai</h4>
        <a href="#">Email</a>
        <a href="#">Telefonas</a>
    </div>
    <div>
        <h4>Social</h4>
        <a href="#">Facebook</a>
        <a href="#">Instagram</a>
    </div>
</footer>

<!-- Modal -->
<div class="modal" id="modal">
    <div class="modal-content">
        <span class="close" id="closeModal">&times;</span>
        <p id="modalText">Modal informacija</p>
    </div>
</div>

<script>
    // Hamburger toggle
    const hamburger = document.getElementById('hamburger');
    const menu = document.getElementById('menu');
    hamburger.addEventListener('click', () => menu.classList.toggle('active'));

    // Modal logic
    const modal = document.getElementById('modal');
    const closeModal = document.getElementById('closeModal');
    function showModal(text) {
        document.getElementById('modalText').innerText = text;
        modal.style.display = 'flex';
    }
    closeModal.onclick = () => modal.style.display = 'none';
    window.onclick = (e) => { if(e.target==modal) modal.style.display='none'; }

    // Load recommendations
    async function loadRecommendations(){
        const res = await fetch('/api/rekomendacijos');
        const rekomendacijos = await res.json();
        const container = document.getElementById('books'); // Naudojame tą patį div
        container.innerHTML = '';

        rekomendacijos.forEach(r => {
            container.innerHTML += `
                <div class="book-card">
                    <h3>${r.knyga.pavadinimas}</h3>
                    <p><strong>Autorius:</strong> ${r.knyga.autorius}</p>
                    <p><strong>Vartotojas:</strong> ${r.naudotojas}</p>
                    <p>${r.komentaras}</p>
                    <p><strong>Įvertinimas:</strong> ${r.ivertinimas}/5</p>
                </div>
            `;
        });
    }

    // Kviečiame funkciją puslapio įkrovimo metu
    loadRecommendations();

    // Form submit
    document.getElementById('rekomForm').addEventListener('submit', async e=>{
        e.preventDefault();

        const token = localStorage.getItem('token');
        if(!token){
            showModal('Jūs turite būti prisijungę, kad galėtumėte palikti rekomendaciją.');
            return;
        }

        const data = {
            knyga_id: document.getElementById('knygaSelect').value,
            naudotojas: document.getElementById('naudotojas').value,
            komentaras: document.getElementById('komentaras').value,
            ivertinimas: parseInt(document.getElementById('ivertinimas').value)
        };

        try {
            const res = await fetch('/api/rekomendacijos', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Authorization': `Bearer ${token}`
                },
                body: JSON.stringify(data)
            });

            if(res.ok){
                showModal('Rekomendacija pridėta sėkmingai!');
                e.target.reset();
                loadRecommendations();
            } else {
                const err = await res.json();
                showModal('Įvyko klaida: ' + (err.message || res.statusText));
            }
        } catch(error){
            showModal('Tinklo klaida: ' + error.message);
        }
    });

    document.getElementById('loginForm').addEventListener('submit', async e => {
        e.preventDefault();
        const email = document.getElementById('email').value;
        const password = document.getElementById('password').value;

        try {
            const res = await fetch('/api/login', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ email, password })
            });
            
            if(res.ok){
                const data = await res.json();
                localStorage.setItem('token', data.access_token); // JWT token
                
                // Pašaliname login formą
                const loginForm = document.getElementById('loginForm');
                loginForm.remove();

                // Parodome vartotojo info
                const main = document.querySelector('main');
                const info = document.createElement('p');
                info.id = 'loginInfo';
                info.textContent = `Prisijungta kaip ${email}`;
                info.style.marginTop = '1rem';
                main.insertBefore(info, main.firstChild);

                showModal('Prisijungta sėkmingai!');
            } else {
                const err = await res.json();
                showModal('Klaida: ' + (err.message || res.statusText));
            }
        } catch(error){
            showModal('Tinklo klaida: ' + error.message);
        }
    });

    // Feather icons replacement
    feather.replace();
</script>

</body>
</html>
