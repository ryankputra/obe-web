## GitHub Copilot Chat

- Extension Version: 0.25.1 (prod)
- VS Code: vscode/1.98.2
- OS: Linux

## Network

User Settings:
```json
  "github.copilot.advanced.debug.useElectronFetcher": true,
  "github.copilot.advanced.debug.useNodeFetcher": false,
  "github.copilot.advanced.debug.useNodeFetchFetcher": true
```

Connecting to https://api.github.com:
- DNS ipv4 Lookup: 20.205.243.168 (609 ms)
- DNS ipv6 Lookup: Error (1254 ms): getaddrinfo ENOTFOUND api.github.com
- Proxy URL: None (8 ms)
- Electron fetch (configured): HTTP 200 (522 ms)
- Node.js https: HTTP 200 (1574 ms)
- Node.js fetch: HTTP 200 (933 ms)
- Helix fetch: HTTP 200 (986 ms)

Connecting to https://api.individual.githubcopilot.com/_ping:
- DNS ipv4 Lookup: 140.82.114.22 (163 ms)
- DNS ipv6 Lookup: Error (5252 ms): getaddrinfo ENOTFOUND api.individual.githubcopilot.com
- Proxy URL: None (108 ms)
- Electron fetch (configured): Error (2811 ms): Error: net::ERR_CERT_DATE_INVALID
    at SimpleURLLoaderWrapper.<anonymous> (node:electron/js2c/utility_init:2:10511)
    at SimpleURLLoaderWrapper.emit (node:events:518:28)
- Node.js https: Error (1208 ms): Error: certificate is not yet valid
    at TLSSocket.onConnectSecure (node:_tls_wrap:1677:34)
    at TLSSocket.emit (node:events:518:28)
    at TLSSocket._finishInit (node:_tls_wrap:1076:8)
    at TLSWrap.ssl.onhandshakedone (node:_tls_wrap:862:12)
- Node.js fetch: Error (1110 ms): TypeError: fetch failed
    at node:internal/deps/undici/undici:13502:13
    at processTicksAndRejections (node:internal/process/task_queues:95:5)
    at yI._fetch (/home/ryan2/.vscode/extensions/github.copilot-chat-0.25.1/dist/extension.js:704:6089)
    at /home/ryan2/.vscode/extensions/github.copilot-chat-0.25.1/dist/extension.js:735:134
    at Sw.h (file:///usr/share/code/resources/app/out/vs/workbench/api/node/extensionHostProcess.js:112:41551)
  Error: certificate is not yet valid
      at TLSSocket.onConnectSecure (node:_tls_wrap:1677:34)
      at TLSSocket.emit (node:events:518:28)
      at TLSSocket._finishInit (node:_tls_wrap:1076:8)
      at TLSWrap.ssl.onhandshakedone (node:_tls_wrap:862:12)
- Helix fetch: Error (1174 ms): FetchError: certificate is not yet valid
    at kqe (/home/ryan2/.vscode/extensions/github.copilot-chat-0.25.1/dist/extension.js:301:29553)
    at processTicksAndRejections (node:internal/process/task_queues:95:5)
    at pqt (/home/ryan2/.vscode/extensions/github.copilot-chat-0.25.1/dist/extension.js:301:31578)
    at EB.fetch (/home/ryan2/.vscode/extensions/github.copilot-chat-0.25.1/dist/extension.js:705:2495)
    at /home/ryan2/.vscode/extensions/github.copilot-chat-0.25.1/dist/extension.js:735:134
    at Sw.h (file:///usr/share/code/resources/app/out/vs/workbench/api/node/extensionHostProcess.js:112:41551)

## Documentation

In corporate networks: [Troubleshooting firewall settings for GitHub Copilot](https://docs.github.com/en/copilot/troubleshooting-github-copilot/troubleshooting-firewall-settings-for-github-copilot).