import puppeteer from 'puppeteer';

(async () => {
    const browser = await puppeteer.launch();
    const page = await browser.newPage();
    page.on('console', msg => console.log('PAGE LOG:', msg.text()));
    page.on('pageerror', error => console.log('PAGE ERROR:', error.message));
    
    try {
        await page.goto('http://localhost:8000', { waitUntil: 'networkidle2', timeout: 5000 });
    } catch (e) {
        console.log("Navigation timeout or error:", e.message);
    }
    
    const appContent = await page.evaluate(() => {
        const app = document.getElementById('app');
        return app ? app.innerHTML : 'No #app';
    });
    console.log('App Content:', appContent);
    await browser.close();
})();
