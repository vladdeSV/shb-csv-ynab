import promptSync from 'prompt-sync';
import puppeteer from 'puppeteer';
import qrcode from 'qrcode-terminal';
import { promises as fs } from 'fs';

async function main() {
    // Prompt the user for their Swedish SSN.
    const prompt = promptSync({ sigint: true });
    const ssn = prompt('Please enter your Swedish SSN: ');

    // Launch Puppeteer and navigate to the login page.
    const browser = await puppeteer.launch({ headless: false });
    const page = await browser.newPage();
    await page.goto('https://secure.handelsbanken.se/logon/se/priv/sv/mbidqr/');

    // Add an event listener for the response event of the page.
    page.on('response', async (response) => {
        const request = response.request();
        const url = request.url();

        // Check if the request URL matches the desired URL.
        if (url.startsWith('https://secure.handelsbanken.se/bb/gls2/aa/privmbidqrwebse/authenticate/1.0')) {

            // Parse the response body and look for the "result":"AUTHORIZED" value.
            const responseBody = await response.json();

            const bankIdToken = responseBody?.qrStartToken;
            if (bankIdToken) {
                console.log('\n\n\n');
                qrcode.generate(bankIdToken);
            }

            if (responseBody.result === 'AUTHORIZED') {
                // Once the user is authorized, proceed with the rest of the script.
                await page.waitForNavigation({ waitUntil: 'networkidle0', timeout: 0 });
                await page.goto('https://secure.handelsbanken.se/se/private/sv/#!/accounts_and_cards/account_transactions');

                // Read the custom JavaScript file.
                const customJs = await fs.readFile('./custom.js', 'utf-8');

                // Run the custom JavaScript in the context of the loaded page.
                await page.evaluate(customJs);

                // Close the browser.
                await browser.close();
            }
        }
    });

    // Log in using the provided SSN and Mobilt BankID.
    await page.waitForSelector('#userId');
    await page.type('#userId', ssn);
    await page.click('form button');

    /*
// Await the promise to resolve, which indicates that the page has been redirected.
await waitForNetworkIdle(page, 4000, 0);

// Navigate to the desired page (if needed).
await page.goto('https://secure.handelsbanken.se/se/private/sv/#!/accounts_and_cards/account_transactions');

// Read the custom JavaScript file.
const customJs = await fs.readFile('./custom.js', 'utf-8');

// Run the custom JavaScript in the context of the loaded page.
await page.evaluate(customJs);

// Close the browser.
await browser.close();
*/
}

/*
const waitForNetworkIdle = (page, timeout = 4000, maxConnections = 0) => {
  return new Promise(async (resolve) => {
    let connections = 0;
    let timer;

    const onIdle = () => {
      clearTimeout(timer);
      timer = setTimeout(() => {
        page.removeListener('request', onRequest);
        page.removeListener('requestfinished', onRequestFinished);
        page.removeListener('requestfailed', onRequestFinished);
        resolve();
      }, timeout);
    };

    const onRequest = () => {
      connections += 1;
      if (connections > maxConnections) {
        clearTimeout(timer);
      }
    };

    const onRequestFinished = () => {
      connections -= 1;
      if (connections <= maxConnections) {
        onIdle();
      }
    };

    page.on('request', onRequest);
    page.on('requestfinished', onRequestFinished);
    page.on('requestfailed', onRequestFinished);

    onIdle();
  });
};
*/

main().catch((error) => {
    console.error('An error occurred:', error);
});
