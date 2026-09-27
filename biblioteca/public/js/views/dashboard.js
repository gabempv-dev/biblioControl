document.addEventListener('DOMContentLoaded', () => {
    fetch('/dashboard/tl')
        .then((res) => {
            if (!res.ok) {
                throw new Error('Respuesta no válida del servidor: ' + res.status);
            }
            return res.json();
        })
        .then((data) => {
            const totalLibros = document.getElementById('total-libros');
            const totalPrestados = document.getElementById('total-prestados');
            const totalLectores = document.getElementById('total-lectores');
            const prestamosVencidos = document.getElementById('prestamos-vencidos');

            if (totalLibros) totalLibros.textContent = data.total_libros ?? 0;
            if (totalPrestados) totalPrestados.textContent = data.total_prestados ?? 0;
            if (totalLectores) totalLectores.textContent = data.total_lectores ?? 0;
            if (prestamosVencidos) prestamosVencidos.textContent = data.prestamos_vencidos ?? 0;
        })
        .catch((err) => {
            console.error('Error al cargar las métricas del dashboard:', err);
        });
});