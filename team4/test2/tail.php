<footer style="position: fixed; bottom: 0; right: 20px; left: 20px; display: flex; justify-content: space-between; align-items: center; padding: 10px 16px; background: #222; color: #eee; font-size: 14px; border-top: 1px solid #444; z-index: 1000;">
    <div class="footer-info">
        &copy; 2025 gatsby_movie_introduce
    </div>
    <div class="footer-buttons">
        <button id="contrastBtn" style="padding:8px 12px; margin-right:5px; border-radius:4px; cursor:pointer;">고대비 모드</button>
        <button id="defaultBtn" style="padding:8px 12px; border-radius:4px; cursor:pointer;">기본 모드</button>
    </div>
</footer>

<script>
if(localStorage.getItem('highContrast') === 'on'){
    document.body.classList.add('high-contrast');
}

document.getElementById('contrastBtn').addEventListener('click', () => {
    document.body.classList.add('high-contrast');
    localStorage.setItem('highContrast', 'on');
});
document.getElementById('defaultBtn').addEventListener('click', () => {
    document.body.classList.remove('high-contrast');
    localStorage.setItem('highContrast', 'off');
});
</script>
</body>
</html>
