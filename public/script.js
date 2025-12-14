// --- Hamburger Menu ---
const hamburger = document.getElementById('hamburger');
const menu = document.getElementById('menu');

hamburger.addEventListener('click', () => menu.classList.toggle('active'));

// Close menu when link is clicked (mobile)
document.querySelectorAll('#menu a').forEach(link => {
    link.addEventListener('click', () => menu.classList.remove('active'));
});

// --- Modal ---
const modal = document.getElementById('modal');
const closeModal = document.getElementById('closeModal');

function showModal(text) {
    document.getElementById('modalText').innerText = text;
    modal.style.display = 'flex';
}

closeModal.onclick = () => modal.style.display = 'none';
window.onclick = (e) => { if(e.target==modal) modal.style.display='none'; }

// --- Load Books from API ---
async function loadBooks() {
    try {
        const res = await fetch('/api/knygos');
        if (!res.ok) throw new Error('Nepavyko užkrauti knygų');
        const books = await res.json();
        const container = document.getElementById('books');
        const select = document.getElementById('knygaSelect');
        if(container) container.innerHTML = '';
        if(select) select.innerHTML = '';
        books.forEach(b => {
            if(container) container.innerHTML += `
                <div class="book-card">
                    <h3>${b.pavadinimas}</h3>
                    <p>${b.autorius}</p>
                    <p>${b.aprasymas || ''}</p>
                </div>`;
            if(select) select.innerHTML += `<option value="${b.id}">${b.pavadinimas}</option>`;
        });
    } catch(err) {
        console.error(err);
        showModal('Knygų sąrašas negalimas');
    }
}

// --- Submit Recommendation Form ---
const rekomForm = document.getElementById('rekomForm');
if(rekomForm){
    rekomForm.addEventListener('submit', async e => {
        e.preventDefault();
        const data = {
            knyga_id: document.getElementById('knygaSelect').value,
            naudotojas: document.getElementById('naudotojas').value,
            komentaras: document.getElementById('komentaras').value,
            ivertinimas: document.getElementById('ivertinimas').value
        };
        try {
            const res = await fetch('/api/rekomendacijos', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify(data)
            });
            if(res.ok){
                showModal('Rekomendacija pridėta sėkmingai!');
                e.target.reset();
                loadBooks();
            } else {
                showModal('Įvyko klaida');
            }
        } catch(err){
            console.error(err);
            showModal('Įvyko klaida');
        }
    });
}

// --- Initialize ---
document.addEventListener('DOMContentLoaded', loadBooks);

// --- Feather icons ---
if(window.feather) feather.replace();
