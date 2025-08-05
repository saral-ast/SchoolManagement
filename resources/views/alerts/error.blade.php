@if (session('error'))
    <div class="alert alert-danger" role="alert">
        {{ session('error') }}
    </div>
@endif

<script>
    window.addEventListener('DOMContentLoaded', (event) => {
        const alertBox = document.querySelector('.alert-danger');
        if (alertBox) {
            setTimeout(() => {
                alertBox.style.transition = 'opacity 0.5s ease';
                alertBox.style.opacity = '0';
                setTimeout(() => alertBox.remove(), 500); // Remove after fade out
            }, 5000); // 5000 milliseconds = 5 seconds visible
        }
    });
</script> 