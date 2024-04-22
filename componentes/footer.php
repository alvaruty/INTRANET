<style>
/* Estilos del pie de página */
.footer {
    background-color: #2496ca;
    color: #fff;
    padding: 20px 0;
}

.footer-container {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    max-width: 1200px;
    margin: 0 auto;
    padding: 0 20px;
}

.footer-section {
    flex: 1;
    margin-right: 20px;
}

.footer-section h3 {
    margin-bottom: 15px;
    font-size: 1.2em;
}

.footer-section ul {
    list-style: none;
    padding: 0;
}

.footer-section ul li {
    margin-bottom: 10px;
}

.footer-section ul li a {
    color: #fff;
    text-decoration: none;
    transition: color 0.3s ease;
}

.footer-section ul li a:hover {
    color: #e0f3ff;
}

.social-icons {
    display: flex;
    gap: 10px;
}

.social-icons li a img {
    width: 30px;
    height: auto;
}

.footer-bottom {
    margin-top: 20px;
    text-align: center;
    background-color: #444;
    padding: 10px 0;
}

.footer-bottom p {
    margin: 0;
    font-size: 0.9em;
}

</style>
<footer class="footer">
        <div class="footer-container">
            <div class="footer-section">
                <h3>Contacto</h3>
                <ul>
                    <li><a href="tel:+34918012657">+34 918 012 657</a></li>
                    <li><a href="mailto:secretaria@ieslassalinas.org">secretaria@ieslassalinas.org</a></li>
                    <li><a href="https://www.google.com/maps?ll=40.109438,-3.660466&z=13&t=m&hl=es&gl=ES&mapclient=embed&cid=5483106900136000276" target="_blank">Dirección</a></li>
                </ul>
            </div>
            <div class="footer-section">
                <h3>Enlaces</h3>
                <ul>
                    <li><a href="#">Delphos</a></li>
                    <li><a href="#">J.C.C.M</a></li>
                    <li><a href="/subpaginas/enlaces.php">Enlaces</a></li>
                </ul>
            </div>
            <div class="footer-section">
                <h3>Redes Sociales</h3>
                <ul class="social-icons">
                    <li><a href="https://www.facebook.com/ieslassalinas/?locale=es_ES"><img src="/imagenes/facebook.png" alt="Facebook"></a></li>
                    <li><a href="https://www.instagram.com/ies_las_salinas/?hl=es"><img src="/imagenes/instagram.png" alt="Instagram"></a></li>
                    <!-- Agrega más enlaces de redes sociales según sea necesario -->
                </ul>
            </div>
        </div>
    </footer>