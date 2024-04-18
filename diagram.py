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
    with Cluster("Application in Server"):
        apps = Server("Application")
        website = Server("Website")
    website >> Edge(color="darkgreen") << Nginx("Web server with NGINX")
    apps >> Edge(color="orange") << MySQL("Database with MySQL")
    apps >> Edge(color="pink") >> CephOsd("Object Storage with MinIO")
    apps >> Edge(color="darkblue") >> SimpleEmailServiceSesEmail('SMTP Mail')
    website >> Edge(label="API", color="darkblue") << apps
