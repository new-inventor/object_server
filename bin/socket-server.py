#!/usr/bin/env python3

import signal
import socket
import sys


def handler(signum, frame):
    print('Exit\n')
    sock.send(b'quit\n')
    sock.close()
    sys.exit()

signal.signal(signal.SIGINT, handler)

HOST = '192.168.1.34'  # Standard loopback interface address (localhost)
PORT = 10000        # Port to listen on (non-privileged ports are > 1023)

with socket.socket(socket.AF_INET, socket.SOCK_STREAM) as s:
    s.bind((HOST, PORT))
    s.listen()
    while True:
        conn, addr = s.accept()
        with conn:
            print('Connected by', addr)
            while True:
                data = conn.recv(1024)
                print(data)
                if data == b'quit' or not data:
                    break
                # conn.sendall(data)