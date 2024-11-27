document.getElementById("connect-wallet").addEventListener("click", async function () {
    try {
        // Initialize TON Connect
        const tonConnect = new TonConnect({
            manifestUrl: 'https://earn.frixle.live/tonconnect-manifest.json'
        });

        // Connect to the wallet
        const wallet = await tonConnect.connectWallet();

        // Display wallet address
        document.getElementById("wallet-address").innerText = `Wallet Address: ${wallet.account.address}`;
        console.log("Connected to wallet:", wallet);

    } catch (error) {
        console.error("Error connecting to TON Wallet:", error);
    }
});
