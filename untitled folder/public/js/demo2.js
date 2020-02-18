;
function getDivOnClick() {
    dragDropWindow2.style.display = "block";
    // dragDropWindow3.style.display = "block";
    // dragDropWindow4.style.display = "block";
    var textArea = document.querySelector(".textspace");
    var dragText =  document.querySelector("#dragDropWindow2 h4");
    dragText.innerHTML = textArea.value;
}

(function () {

    var listDiv = document.getElementById("list"),

        showConnectionInfo = (s) => {
            listDiv.innerHTML = s;
            listDiv.style.display = "block";
        },
        hideConnectionInfo = () => {
            listDiv.style.display = "none";
        },
        connections = [],
        updateConnections = (conn, remove) => {
            if (!remove) connections.push(conn);
            else {
                var idx = -1;
                for (var i = 0; i < connections.length; i++) {
                    if (connections[i] == conn) {
                        idx = i;
                        break;
                    }
                }
                if (idx != -1) connections.splice(idx, 1);
            }
            if (connections.length > 0) {
                var s = "<span><strong>Connections</strong></span><br/><br/><table><tr><th>Scope</th><th>Source</th><th>Target</th></tr>";
                for (var j = 0; j < connections.length; j++) {
                    s += "<tr><td>" + connections[j].scope + "</td>" + "<td>" + connections[j].sourceId + "</td><td>" + connections[j].targetId + "</td></tr>";
                }
                showConnectionInfo(s);
            } else
                hideConnectionInfo();
        };

    jsPlumb.ready(() => {

        var instance = jsPlumb.getInstance({
            DragOptions: { cursor: 'pointer', zIndex: 2000 },
            PaintStyle: { stroke: '#666' },
            EndpointHoverStyle: { fill: "orange" },
            HoverPaintStyle: { stroke: "orange" },
            EndpointStyle: { width: 30, height: 16, stroke: '#666' },
            Endpoint: "Rectangle",
            Anchors: ["TopCenter", "TopCenter"],
            Container: "canvas"
        });

        // suspend drawing and initialise.
        instance.batch(() => {

            // configure some drop options for use by all endpoints.
            var exampleDropOptions = {
                tolerance: "touch",
                hoverClass: "dropHover",
                activeClass: "dragActive"
            };

            //
            // first example endpoint.  it's a 25x21 rectangle (the size is provided in the 'style' arg to the Endpoint),
            // and it's both a source and target.  the 'scope' of this Endpoint is 'exampleConnection', meaning any connection
            // starting from this Endpoint is of type 'exampleConnection' and can only be dropped on an Endpoint target
            // that declares 'exampleEndpoint' as its drop scope, and also that
            // only 'exampleConnection' types can be dropped here.
            //
            // the connection style for this endpoint is a Bezier curve (we didn't provide one, so we use the default), with a strokeWidth of
            // 5 pixels, and a gradient.
            //
            // there is a 'beforeDrop' interceptor on this endpoint which is used to allow the user to decide whether
            // or not to allow a particular connection to be established.
            //
            var exampleColor = "#00f";
            var exampleEndpoint = {
                endpoint: "Dot",
                paintStyle: { fill: exampleColor },
                isSource: true,
                reattach: true,
                scope: "blue",
                connectorStyle: {
                    gradient: {stops: [
                        [0, exampleColor],
                        [0.5, "#09098e"],
                        [1, exampleColor]
                    ]},
                    strokeWidth: 6,
                    stroke: exampleColor
                },
				maxConnections: 1,
                isTarget: true,
                dropOptions: exampleDropOptions
            };

            //
            // the second example uses a Dot of radius 15 as the endpoint marker, is both a source and target,
            // and has scope 'exampleConnection2'.
            //
            var color2 = "#316b31";
            var exampleEndpoint2 = {
                endpoint: ["Dot", { radius: 11 }],
                paintStyle: { fill: color2 },
                isSource: true,
                scope: "green",
                connectorStyle: { stroke: color2, strokeWidth: 6 },
                connector: ["Bezier", { curviness: 63 } ],
                maxConnections: 1,
                isTarget: true,
                dropOptions: exampleDropOptions
            };

            //
            // the third example uses a Dot of radius 17 as the endpoint marker, is both a source and target, and has scope
            // 'exampleConnection3'.  it uses a Straight connector, and the Anchor is created here (bottom left corner) and never
            // overriden, so it appears in the same place on every element.
            //
            // this example also demonstrates the beforeDetach interceptor, which allows you to intercept
            // a connection detach and decide whether or not you wish to allow it to proceed.
            //
            var example3Color = "rgba(229,219,61,0.5)";
            var exampleEndpoint3 = {
                endpoint: ["Dot", {radius: 17} ],
                anchor: "BottomLeft",
                paintStyle: { fill: example3Color, opacity: 0.8 },
                isSource: true,
                scope: 'yellow',
                connectorStyle: {
                    stroke: example3Color,
                    strokeWidth: 4
                },
                connector: "Straight",
                isTarget: true,
                dropOptions: exampleDropOptions,
                beforeDetach: function (conn) {
                    return confirm("Detach connection?");
                },
                onMaxConnections: function (info) {
                    alert("Cannot drop connection " + info.connection.id + " : maxConnections has been reached on Endpoint " + info.endpoint.id);
                }
            };

            // setup some empty endpoints.  again note the use of the three-arg method to reuse all the parameters except the location
            // of the anchor (purely because we want to move the anchor around here; you could set it one time and forget about it though.)
            var e1 = instance.addEndpoint('dragDropWindow1', { anchor: [0.5, 1, 0, 1] }, exampleEndpoint2);

            // setup some DynamicAnchors for use with the blue endpoints
            // and a function to set as the maxConnections callback.
            var anchors = [
                    [1, 0.2, 1, 0],
                    [0.8, 1, 0, 1],
                    [0, 0.8, -1, 0],
                    [0.2, 0, 0, -1]
                ],
                maxConnectionsCallback = (info) => {
                    alert("Maximum connection has been reached on that node");
                };

            var e1 = instance.addEndpoint("dragDropWindow1", { anchor: anchors });
            // you can bind for a maxConnections callback using a standard bind call, but you can also supply 'onMaxConnections' in an Endpoint definition - see exampleEndpoint3 above.
            e1.bind("maxConnections", maxConnectionsCallback);

            var e2 = instance.addEndpoint('dragDropWindow2', exampleEndpoint2);
            // again we bind manually. it's starting to get tedious.  but now that i've done one of the blue endpoints this way, i have to do them all...
            e2.bind("maxConnections", maxConnectionsCallback);
            instance.addEndpoint('dragDropWindow2', { anchor: "RightMiddle" }, exampleEndpoint2);

            // var e3 = instance.addEndpoint("dragDropWindow3", exampleEndpoint2);
            // e3.bind("maxConnections", maxConnectionsCallback);
            // instance.addEndpoint("dragDropWindow3", { anchor: [0.75, 0, 0, -1] }, exampleEndpoint2);

            // var e4 = instance.addEndpoint("dragDropWindow4", exampleEndpoint2);
            // e4.bind("maxConnections", maxConnectionsCallback);
            // instance.addEndpoint("dragDropWindow4", { anchor: [0.25, 0, 0, -1] }, exampleEndpoint2);

            // make .window divs draggable
            instance.draggable(jsPlumb.getSelector(".drag-drop-demo .window"));

            var hideLinks = jsPlumb.getSelector(".drag-drop-demo .hide");
            instance.on(hideLinks, "click", function (e) {
                instance.toggleVisible(this.getAttribute("rel"));
                jsPlumbUtil.consume(e);
            });

            var dragLinks = jsPlumb.getSelector(".drag-drop-demo .drag");
            instance.on(dragLinks, "click", function (e) {
                var s = instance.toggleDraggable(this.getAttribute("rel"));
                this.innerHTML = (s ? 'disable dragging' : 'enable dragging');
                jsPlumbUtil.consume(e);
            });

            var detachLinks = jsPlumb.getSelector(".drag-drop-demo .detach");
            instance.on(detachLinks, "click", function (e) {
                instance.deleteConnectionsForElement(this.getAttribute("rel"));
                jsPlumbUtil.consume(e);
            });

            instance.on(document.getElementById("clear"), "click", function (e) {
                instance.detachEveryConnection();
                showConnectionInfo("");
                jsPlumbUtil.consume(e);
            });

        });

        jsPlumb.fire("jsPlumbDemoLoaded", instance);

    });
})();