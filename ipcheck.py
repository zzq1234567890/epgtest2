import socket
import concurrent.futures
import ipaddress

start_ip = ipaddress.IPv4Address('118.170.0.0')
end_ip = ipaddress.IPv4Address('118.170.255.255')
port = 2390
max_threads = 100

def check_port(ip):
    s = socket.socket(socket.AF_INET, socket.SOCK_STREAM)
    s.settimeout(1)
    try:
        s.connect((str(ip), port))
        return str(ip)
    except:
        return None
    finally:
        s.close()

def main():
    with open('ippy.txt', 'w') as f:
        with concurrent.futures.ThreadPoolExecutor(max_workers=max_threads) as executor:
            future_to_ip = {executor.submit(check_port, ip): ip for ip in ipaddress.summarize_address_range(start_ip, end_ip)}
            for future in concurrent.futures.as_completed(future_to_ip):
                ip = future_to_ip[future]
                try:
                    result = future.result()
                    if result:
                        f.write(result + '\n')
                        print(f"Found open port at: {result}")
                        return  # Stop testing after finding one open port
                except Exception as exc:
                    print(f'{ip} generated an exception: {exc}')

if __name__ == '__main__':
    main()
