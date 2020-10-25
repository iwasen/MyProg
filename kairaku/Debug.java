import java.net.DatagramSocket;
import java.net.DatagramPacket;
import java.net.InetAddress;

public class Debug
{
	static void print(String msg) {
		try {
			if (msg == null)
				msg = "null";
			byte[] b = msg.getBytes("SJIS");

			DatagramSocket socket = new DatagramSocket();
			DatagramPacket packet = new DatagramPacket(b, b.length, InetAddress.getByName("localhost"), 7890);
			
			socket.send(packet);
			socket.close();
		} catch (Exception e) {
		}
	}
}
