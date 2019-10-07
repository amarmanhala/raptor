/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/* global angular */

var heightRowsChanged = [];
var rowsRenderedTimeout;
// auto-dimension of cells (css) need to force align rows in all containers (left and right pinning)
var alignContainers =  function alignContainers ( gridContainer , grid) {
 
    var rows = angular.element(gridContainer + ' .ui-grid .ui-grid-render-container-body .ui-grid-row');
    var pinnedRowsLeft = angular.element(gridContainer + ' .ui-grid .ui-grid-pinned-container-left .ui-grid-row');
    var gridHasRightContainer = grid.hasRightContainer();
    if (gridHasRightContainer) {
            var pinnedRowsRight = angular.element(gridContainer + ' .ui-grid .ui-grid-pinned-container-right .ui-grid-row');
    }

    var bodyContainer = grid.renderContainers.body;

    // get count columns pinned on left
    var columnsPinnedOnLeft = grid.renderContainers.left.renderedColumns.length;

    for (var r = 0; r < heightRowsChanged.length; r++) {
      heightRowsChanged[r].height = 30;
    }
    heightRowsChanged = [];

    for(var r = 0; r < rows.length; r++) {
        // Remove height CSS property to get new height if container resized (slidePanel)
        var elementBody = angular.element(rows[r]).children('div');
        elementBody.css('height', '');
        var elementLeft = angular.element(pinnedRowsLeft[r]).children('div');
        elementLeft.css('height', '');
        if (gridHasRightContainer) {
            var elementRight = angular.element(pinnedRowsRight[r]).children('div');
            elementRight.css('height', '');
        }

        // GET Height when set in auto for each container
        // BODY CONTAINER
        var rowHeight = rows[r].offsetHeight;
        // LEFT CONTAINER
        var pinnedRowLeftHeight = 0;
        if (columnsPinnedOnLeft && pinnedRowsLeft[r] !== undefined) {
            pinnedRowLeftHeight = pinnedRowsLeft[r].offsetHeight;
        }
        // RIGHT CONTAINER
        var pinnedRowRightHeight = 0;
        if (gridHasRightContainer) {
            pinnedRowRightHeight = pinnedRowsRight[r].offsetHeight;
        }
        // LARGEST
        var largest = Math.max(rowHeight, pinnedRowLeftHeight, pinnedRowRightHeight);

        // Apply new row height in each container
        elementBody.css('height', largest);
        elementLeft.css('height', largest);
        elementLeft.children('div').css('height', largest);
        if (gridHasRightContainer) {
            elementRight.css('height', largest);
        }

        // Apply new height in gridRow definition (used by scroll)
        //bodyContainer.renderedRows[r].height = largest;
        var heightRowChanged = bodyContainer.renderedRows[r];
        if(heightRowChanged !== undefined){
            heightRowChanged.height = largest;
            heightRowsChanged.push(heightRowChanged);
        }
    }
    // NEED TO REFRESH CANVAS
    bodyContainer.canvasHeightShouldUpdate = true;

};
// END alignContainers()


