<div class="imageBoxLeft" id="versusBoxImageLeft">
	<img src="<?php echo $imageDefault[0]; ?>"/>
</div>
<div class="imageBoxRight" id="versusBoxImageRight">
	<img src="<?php echo $imageDefault[0]; ?>"/>
</div>
<div class="vs" onclick="showSelectBoxes()"></div>

<div class="selectBoxLeft">
	<div class="versusSelect">
		<select onchange="javascript: versusLeft(this.value);" name="modelo_1">
			<option value="" selected="selected">Select a brand</option>
			<?php do{?> 
				<option  value="<?php echo $row_LeftBox['brand']?>"><?php echo $row_LeftBox['brand']?></option>
			<?php }while  ($row_LeftBox = mysql_fetch_assoc($LeftBox));?>    
		</select>
		<svg version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 306 306" xml:space="preserve">
			<polygon points="270.3,58.65 153,175.95 35.7,58.65 0,94.35 153,247.35 306,94.35"/>
		</svg>
	</div>
	<div class="versusSelect hidden" id="versusSelectYearLeft">
		<span>Select a year</span>
	</div>
	<div class="versusSelect hidden" id="versusSelectModelLeft">
		<span>Select a model</span>
	</div>
	<div id="versusResponseLeft"></div>
</div>

<div class="selectBoxRight">
	<div class="versusSelect">
		<select onchange="javascript: versusRight(this.value);" name="modelo_1">
			<option value="" selected="selected">Select a brand</option>
			<?php do{?> 
				<option  value="<?php echo $row_RightBox['brand']?>"><?php echo $row_RightBox['brand']?></option>
			<?php }while  ($row_RightBox = mysql_fetch_assoc($RightBox));?>    
		</select>
		<svg version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 306 306" xml:space="preserve">
			<polygon points="270.3,58.65 153,175.95 35.7,58.65 0,94.35 153,247.35 306,94.35"/>
		</svg>
	</div>
	<div class="versusSelect hidden" id="versusSelectYearRight">
		<span>Select a year</span>
	</div>
	<div class="versusSelect hidden" id="versusSelectModelRight">
		<span>Select a model</span>
	</div>
	<div id="versusResponseRight"></div>
</div>

<div class="content">
	<div class="dataBox collapseDataBox1">
		<div class="title" onclick="collapseDataBox(1)">
			Performance
			<div class="arrow"></div>
		</div>
		<div class="body">
			<ul>
				<li>
					<div>Maximum speed (km/h)</div>
					<span><label class="maxSpeedLeft">0</label> km/h</span>
					<span><label class="maxSpeedRight">0</label> km/h</span>
				</li>
				<li>
					<div>Acceleration 0-100 km (s)</div>
					<span><label class="acceleration100Left">0</label> s</span>
					<span><label class="acceleration100Right">0</label> s</span>
				</li>
				<li>
					<div>Acceleration 0-1000 m (s)</div>
					<span><label class="acceleration1000Left">0</label> s</span>
					<span><label class="acceleration1000Right">0</label> s</span>
				</li>
				<li>
					<div>Urban consumption (L/100 km)</div>
					<span><label class="urbanConsumptionLeft">0</label> L</span>
					<span><label class="urbanConsumptionRight">0</label> L</span>
				</li>
				<li>
					<div>Extra-urban consumption (L/100km)</div>
					<span><label class="extraUrbanConsumptionLeft">0</label> L</span>
					<span><label class="extraUrbanConsumptionRight">0</label> L</span>
				</li>
				<li>
					<div>Average consumption (L/100km)</div>
					<span><label class="averageConsumptionLeft">0</label> L</span>
					<span><label class="averageConsumptionRight">0</label> L</span>
				</li>
				<li>
					<div>CO2 emissions (g/km)</div>
					<span><label class="emissionsLeft">0</label> g</span>
					<span><label class="emissionsRight">0</label> g</span>
				</li>
			</ul>
		</div>
	</div>
	<div class="dataBox collapseDataBox2">
		<div class="title" onclick="collapseDataBox(2)">
			Dimensions
			<div class="arrow"></div>
		</div>
		<div class="body">
			<ul>
				<li>
					<div>Body type</div>
					<span><label class="bodyTypeLeft">-</label></span>
					<span><label class="bodyTypeRight">-</label></span>
				</li>
				<li>
					<div>Number of doors</div>
					<span><label class="numberOfDoorsLeft">0</label></span>
					<span><label class="numberOfDoorsRight">0</label></span>
				</li>
				<li>
					<div>Weight (kg)</div>
					<span><label class="weightLeft">0</label> kg</span>
					<span><label class="weightRight">0</label> kg</span>
				</li>
				<li>
					<div>Tank Volume (L)</div>
					<span><label class="tankVolumeLeft">0</label> L</span>
					<span><label class="tankVolumeRight">0</label> L</span>
				</li>
				<li>
					<div>Boot Volume (L)</div>
					<span><label class="bootVolumeLeft">0</label> L</span>
					<span><label class="bootVolumeRight">0</label> L</span>
				</li>
				<li>
					<div>Number of places</div>
					<span><label class="numberOfPlacesLeft">0</label></span>
					<span><label class="numberOfPlacesRight">0</label></span>
				</li>
				<li>
					<div>Wheelbase / Front track - rear (cm)</div>
				</li>
			</ul>
			<div class="dimensionsLeft">
				<div class="imgBox">
					<img src="<?php echo $urlWeb ?>images/siluete-left.png">
					<div class="sideUp">1345</div>
					<div class="sideDown">2345</div>
				</div>
				<div class="imgBox">
					<img src="<?php echo $urlWeb ?>images/siluete-front.png">
					<div class="frontUp">1345</div>
					<div class="frontDown">2345</div>
					<div class="frontHeight">1345</div>
					<div class="backUp">1345</div>
					<div class="backDown">2345</div>
				</div>
			</div>
			<div class="dimensionsRight">
				<div class="imgBox">
					<img src="<?php echo $urlWeb ?>images/siluete-left.png">
					<div class="sideUp">1345</div>
					<div class="sideDown">2345</div>
				</div>
				<div class="imgBox">
					<img src="<?php echo $urlWeb ?>images/siluete-front.png">
					<div class="frontUp">1345</div>
					<div class="frontDown">2345</div>
					<div class="frontHeight">1345</div>
					<div class="backUp">1345</div>
					<div class="backDown">2345</div>
				</div>
			</div>
		</div>
	</div>
	<div class="dataBox collapseDataBox3">
		<div class="title" onclick="collapseDataBox(3)">
			Motor
			<div class="arrow"></div>
		</div>
		<div class="body">
			<ul>
				<li>
					<div>Fuel</div>
					<span><label class="maxSpeedLeft">-</label></span>
					<span><label class="maxSpeedRight">-</label></span>
				</li>
				<li>
					<div>Displacement</div>
					<span><label class="acceleration100Left">0</label> s</span>
					<span><label class="acceleration100Right">0</label> s</span>
				</li>
				<li>
					<div>Maximum power HP-kW/rpm</div>
					<span><label class="acceleration1000Left">0</label> HP-kW</span>
					<span><label class="acceleration1000Right">0</label> HP-kW</span>
				</li>
				<li>
					<div>Maximum torque Nm/rpm</div>
					<span><label class="urbanConsumptionLeft">0</label> Nm/rpm</span>
					<span><label class="urbanConsumptionRight">0</label> Nm/rpm</span>
				</li>
				<li>
					<div>Number of cylinders</div>
					<span><label class="extraUrbanConsumptionLeft">0</label></span>
					<span><label class="extraUrbanConsumptionRight">0</label></span>
				</li>
				<li>
					<div>Material block/butt</div>
					<span><label class="averageConsumptionLeft">-</label></span>
					<span><label class="averageConsumptionRight">-</label></span>
				</li>
				<li>
					<div>Bore x stroke (mm)</div>
					<span><label class="emissionsLeft">0</label> mm</span>
					<span><label class="emissionsRight">0</label> mm</span>
				</li>
				<li>
					<div>Distribution</div>
					<span><label class="emissionsLeft">-</label></span>
					<span><label class="emissionsRight">-</label></span>
				</li>
				<li>
					<div>Feeding</div>
					<span><label class="emissionsLeft">-</label></span>
					<span><label class="emissionsRight">-</label></span>
				</li>
				<li>
					<div>Starting the engine</div>
					<span><label class="emissionsLeft">-</label></span>
					<span><label class="emissionsRight">-</label></span>
				</li>
			</ul>
		</div>
	</div>
	<div class="dataBox collapseDataBox4">
		<div class="title" onclick="collapseDataBox(4)">
			Transmission
			<div class="arrow"></div>
		</div>
		<div class="body">
			<ul>
				<li>
					<div>Wheel drive</div>
					<span><label class="maxSpeedLeft">-</label></span>
					<span><label class="maxSpeedRight">-</label></span>
				</li>
				<li>
					<div>Gearbox</div>
					<span><label class="acceleration100Left">0</label></span>
					<span><label class="acceleration100Right">0</label></span>
				</li>
				<li>
					<div>Developments (km / h - 1.000 rpm)</div>
					<span><label class="acceleration1000Left">0</label> rpm</span>
					<span><label class="acceleration1000Right">0</label> rpm</span>
				</li>
			</ul>
		</div>
	</div>
	<div class="dataBox collapseDataBox5">
		<div class="title" onclick="collapseDataBox(5)">
			Chassis
			<div class="arrow"></div>
		</div>
		<div class="body">
			<ul>
				<li>
					<div>Front suspension (structure/spring)</div>
					<span><label class="maxSpeedLeft">-</label></span>
					<span><label class="maxSpeedRight">-</label></span>
				</li>
				<li>
					<div>Rear Suspension (structure/spring)</div>
					<span><label class="maxSpeedLeft">-</label></span>
					<span><label class="maxSpeedRight">-</label></span>
				</li>
				<li>
					<div>Front brakes (mm diameter)</div>
					<span><label class="acceleration1000Left">0</label> mm</span>
					<span><label class="acceleration1000Right">0</label> mm</span>
				</li>
				<li>
					<div>Rear brakes (mm diameter)</div>
					<span><label class="urbanConsumptionLeft">0</label> mm</span>
					<span><label class="urbanConsumptionRight">0</label> mm</span>
				</li>
				<li>
					<div>Direction</div>
					<span><label class="extraUrbanConsumptionLeft">-</label></span>
					<span><label class="extraUrbanConsumptionRight">-</label></span>
				</li>
				<li>
					<div>Front tires</div>
					<span><label class="averageConsumptionLeft">-</label></span>
					<span><label class="averageConsumptionRight">-</label></span>
				</li>
				<li>
					<div>Rear tires</div>
					<span><label class="averageConsumptionLeft">-</label></span>
					<span><label class="averageConsumptionRight">-</label></span>
				</li>
				<li>
					<div>Front wheels</div>
					<span><label class="averageConsumptionLeft">-</label></span>
					<span><label class="averageConsumptionRight">-</label></span>
				</li>
				<li>
					<div>Rear wheels</div>
					<span><label class="averageConsumptionLeft">-</label></span>
					<span><label class="averageConsumptionRight">-</label></span>
				</li>
			</ul>
		</div>
	</div>
</div>

<script type="text/javascript">
	function versusLeft(valor){
		$.ajax({
		    type: 'POST',
		    url: url+'pages/versus/year-left.php',
		    data: 'brand='+valor,
		    success: function(htmlres){
				$('#versusSelectYearLeft').html(htmlres);
				$('#versusSelectYearLeft').css({"pointer-events":"visible","opacity":"1"});
				$('#versusSelectModelLeft').css({"pointer-events":"none","opacity":"0.3"});
		    }
	    });
	}

	function versusRight(valor){
		$.ajax({
		    type: 'POST',
		    url: url+'pages/versus/year-right.php',
		    data: 'brand='+valor,
		    success: function(htmlres){
				$('#versusSelectYearRight').html(htmlres);
				$('#versusSelectYearRight').css({"pointer-events":"visible","opacity":"1"});
				$('#versusSelectModelRight').css({"pointer-events":"none","opacity":"0.3"});
		    }
	    });
	}

	function showSelectBoxes(){
		$('.selectBoxLeft').toggleClass('collapseBox');
		$('.selectBoxRight').toggleClass('collapseBox');
		$('.vs').toggleClass('opacityButton');
	}

	function collapseDataBox(type){
		$('.collapseDataBox'+ type+' .body').toggleClass('collapseBox');
		$('.collapseDataBox'+ type+' .title .arrow').toggleClass('arrowCollapse');
	}
</script>