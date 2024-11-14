

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const sidebar = document.getElementById('sidebar');
            const overlay = document.getElementById('overlay');
            const toggleBtn = document.getElementById('sidebarToggle');
            const closeBtn = document.getElementById('closeSidebar');
            const currentPage = new URLSearchParams(window.location.search).get('page') || 'contenu';

            // Fonction pour ouvrir/fermer la sidebar
            function toggleSidebar() {
                sidebar.classList.toggle('active');
                overlay.classList.toggle('active');
            }

            // Fonction pour fermer la sidebar
            function closeSidebar() {
                sidebar.classList.remove('active');
                overlay.classList.remove('active');
            }

            // Event listeners
            toggleBtn.addEventListener('click', toggleSidebar);
            closeBtn.addEventListener('click', closeSidebar);
            overlay.addEventListener('click', closeSidebar);

            // Gérer les liens actifs
            const navLinks = document.querySelectorAll('.nav-link');
            navLinks.forEach(link => {
                const linkPage = new URLSearchParams(link.href.split('?')[1]).get('page');
                if (linkPage === currentPage) {
                    link.classList.add('bg-blue-500', 'text-white');
                }
                
                link.addEventListener('click', () => {
                    navLinks.forEach(l => l.classList.remove('bg-blue-500', 'text-white'));
                    link.classList.add('bg-blue-500', 'text-white');
                    if (window.innerWidth < 1024) {
                        closeSidebar();
                    }
                });
            });

            // Fermer la sidebar si la fenêtre est redimensionnée au-dessus de 1024px
            window.addEventListener('resize', () => {
                if (window.innerWidth >= 1024) {
                    closeSidebar();
                }
            });
        });
    </script>
</body>
</html>