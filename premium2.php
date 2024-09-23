<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>BNB Transaction</title>
        <script src="https://cdn.jsdelivr.net/npm/web3@1.7.5/dist/web3.min.js"></script>
    </head>
    <body>
        <h1>Send BNB to Contract</h1>
        <p>Enter the amount of BNB you want to send:</p>
        <input type="number" id="bnbAmount" placeholder="Amount in BNB" step="0.01">
        <button onclick="sendBNB()">Send BNB</button>

        <h2>Transaction Status</h2>
        <p id="txStatus">Waiting for transaction...</p>

        <script>
            // ABI of the contract
            const contractABI = [
                {
                    "inputs": [],
                    "stateMutability": "nonpayable",
                    "type": "constructor"
                },
                {
                    "anonymous": false,
                    "inputs": [
                        {
                            "indexed": false,
                            "internalType": "address",
                            "name": "sender",
                            "type": "address"
                        },
                        {
                            "indexed": false,
                            "internalType": "uint256",
                            "name": "amount",
                            "type": "uint256"
                        }
                    ],
                    "name": "Received",
                    "type": "event"
                },
                {
                    "inputs": [
                        {
                            "internalType": "address",
                            "name": "",
                            "type": "address"
                        }
                    ],
                    "name": "balances",
                    "outputs": [
                        {
                            "internalType": "uint256",
                            "name": "",
                            "type": "uint256"
                        }
                    ],
                    "stateMutability": "view",
                    "type": "function"
                },
                {
                    "inputs": [],
                    "name": "getContractBalance",
                    "outputs": [
                        {
                            "internalType": "uint256",
                            "name": "",
                            "type": "uint256"
                        }
                    ],
                    "stateMutability": "view",
                    "type": "function"
                },
                {
                    "inputs": [],
                    "name": "owner",
                    "outputs": [
                        {
                            "internalType": "address",
                            "name": "",
                            "type": "address"
                        }
                    ],
                    "stateMutability": "view",
                    "type": "function"
                },
                {
                    "inputs": [],
                    "name": "withdraw",
                    "outputs": [],
                    "stateMutability": "nonpayable",
                    "type": "function"
                },
                {
                    "stateMutability": "payable",
                    "type": "receive"
                }
            ];

            // Address of the deployed contract
            const contractAddress = '0x04a6054FFbdA3e7d4c55eAEe053E24f9aa33E893';  // Replace with your contract's address

            // Web3 setup
            let web3;
            let contract;

            if (window.ethereum) {
                web3 = new Web3(window.ethereum);
                try {
                    // Request account access if needed
                    window.ethereum.enable().then(() => {
                        console.log("DApp connected to MetaMask");
                    });
                } catch (error) {
                    console.error("User denied account access");
                }
            } else if (window.web3) {
                web3 = new Web3(window.web3.currentProvider);
            } else {
                alert("Please install MetaMask to use this DApp.");
            }

            // Initialize the contract
            contract = new web3.eth.Contract(contractABI, contractAddress);

            // Function to send BNB to the contract
            async function sendBNB() {
                const bnbAmount = document.getElementById('bnbAmount').value;

                if (!bnbAmount || isNaN(bnbAmount) || bnbAmount <= 0) {
                    alert('Please enter a valid BNB amount');
                    return;
                }

                const accounts = await web3.eth.getAccounts();
                const account = accounts[0];

                contract.methods.getContractBalance().call()
                    .then(balance => {
                        console.log("Contract balance:", balance);
                    })
                    .catch(error => {
                        console.error("Error fetching contract balance:", error);
                    });

                // Send BNB to the contract
                web3.eth.sendTransaction({
                    from: account,
                    to: contractAddress,
                    value: web3.utils.toWei(bnbAmount, 'ether')
                })
                .on('transactionHash', function(hash) {
                    document.getElementById('txStatus').innerText = 'Transaction Sent! TX ID: ' + hash;
                })
                .on('receipt', function(receipt) {
                    document.getElementById('txStatus').innerText = 'Transaction Confirmed! TX ID: ' + receipt.transactionHash;
                })
                .on('error', function(error) {
                    document.getElementById('txStatus').innerText = 'Transaction Failed: ' + error.message;
                    console.error("Transaction error:", error);
                });
            }

        </script>
    </body>
</html>
