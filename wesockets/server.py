import asyncio
import websockets
import json

clients = set()

async def broadcast_users_count():
    """Envía a todos los clientes el número de usuarios conectados"""
    while True:
        if clients:
            message = json.dumps({
                "type": "users_count",
                "count": len(clients)
            })
            disconnected = set()
            for client in clients:
                try:
                    await client.send(message)
                except websockets.exceptions.ConnectionClosed:
                    disconnected.add(client)
            clients.difference_update(disconnected)
        await asyncio.sleep(1)  # actualizar cada segundo

async def handler(websocket):
    global clients
    clients.add(websocket)
    try:
        async for message in websocket:
            # Reenviar mensajes de chat
            data = json.dumps({"type": "chat", "msg": message})
            disconnected = set()
            for client in clients:
                try:
                    await client.send(data)
                except websockets.exceptions.ConnectionClosed:
                    disconnected.add(client)
            clients.difference_update(disconnected)
    except websockets.exceptions.ConnectionClosed:
        pass
    finally:
        clients.discard(websocket)

async def main():
    server = await websockets.serve(handler, "localhost", 8000)
    print("🚀 Servidor WebSocket en ws://localhost:8000")
    # Corremos la tarea de usuarios conectados junto con el servidor
    await asyncio.gather(
        server.wait_closed(),
        broadcast_users_count()
    )

asyncio.run(main())
