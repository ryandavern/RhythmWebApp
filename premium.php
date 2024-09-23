<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Web3Modal Example</title>
</head>
<body>
    <h1>Connect to MetaMask</h1>
    <button id="connectButton">Connect Wallet</button>
    <button id="callContractButton" style="display:none;">Call Contract</button>

    <script src="https://unpkg.com/web3modal"></script>
    <script src="https://cdn.jsdelivr.net/npm/@walletconnect/web3-provider@1.7.1/dist/umd/index.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/ethers@5.7.0/dist/ethers.umd.min.js"></script>

    <script>
        async function init() {
            const Web3Modal = window.Web3Modal.default;
            const WalletConnectProvider = window.WalletConnectProvider.default;
            const ethers = window.ethers;

            // Define provider options (you can add other providers like WalletConnect)
            const providerOptions = {
                walletconnect: {
                    package: WalletConnectProvider, // required
                    options: {
                        infuraId: "3791550a10d34840b22514dea2c0b925" // required; you can use your Infura project ID
                    }
                }
            };

            // Initialize Web3Modal
            const web3Modal = new Web3Modal({
                cacheProvider: false, // optional
                providerOptions // required
            });

            let provider;

            // Function to connect wallet
            async function connectWallet() {
                try {
                    provider = await web3Modal.connect();
                    const ethersProvider = new ethers.providers.Web3Provider(provider);
                    const signer = ethersProvider.getSigner();
                    const userAddress = await signer.getAddress();
                    console.log('Connected address:', userAddress);

                    document.getElementById('callContractButton').style.display = 'block';
                } catch (err) {
                    console.error('Failed to connect wallet', err);
                }
            }

            // Function to call contract
            async function callContract() {
                const ethersProvider = new ethers.providers.Web3Provider(provider);
                const signer = ethersProvider.getSigner();
                const contractAddress = "YOUR_CONTRACT_ADDRESS"; // Replace with your contract's address
                const abi = [
                    // Replace with your contract's ABI
                    "function yourFunction() public view returns (string)"
                ];
                const contract = new ethers.Contract(contractAddress, abi, signer);

                try {
                    const result = await contract.yourFunction();
                    console.log('Contract call result:', result);
                } catch (error) {
                    console.error('Error calling contract function', error);
                }
            }

            // Event listeners for buttons
            document.getElementById('connectButton').addEventListener('click', connectWallet);
            document.getElementById('callContractButton').addEventListener('click', callContract);
        }

        window.addEventListener('load', init);
    </script>
</body>
</html>
