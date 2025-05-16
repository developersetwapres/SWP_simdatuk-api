from diagrams import Cluster, Diagram, Edge
from diagrams.onprem.storage import CephOsd
from diagrams.onprem.database import MySQL
from diagrams.onprem.network import Nginx
from diagrams.onprem.compute import Server
from diagrams.aws.engagement import SimpleEmailServiceSesEmail

graph_attr = {
    # "fontsize": "14",
}

with Diagram(name="", filename="infrastructure", direction="RL", outformat="jpg", show=False, graph_attr=graph_attr):
    apps = Server("API")
    website = Server("Website")
    webserver = Nginx("Web server")
    database = MySQL("Database with MySQL")
    objectstorage = CephOsd("Object Storage with MinIO")
    email = SimpleEmailServiceSesEmail('SMTP Mail')
    apps >> Edge(color="darkblue") << webserver
    website >> Edge(color="darkblue") << webserver
    objectstorage >> Edge(color="darkblue") << webserver
    apps >> Edge(color="darkblue") << database
    apps >> Edge(color="darkblue") << objectstorage
    apps >> Edge(color="darkblue") << email
