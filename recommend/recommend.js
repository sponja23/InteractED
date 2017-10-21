var recommendInput;
var model;
var postTitlePosition;
var userLabels;
var matrixWidth;
var currentRatings;

var fs = require("fs");
var recommender = require("likely");
var mysql = require("mysql");

var con = mysql.createConnection({
  host: "localhost",
  user: "root",
  password: "root",
  database: "database"
});

con.connect(function(err) {
	if(err) {
		console.log("Error: couldn't connect to database");
		throw err;
	}
	console.log("Database connected");
});

function arrayOfZeros(size) {
	var arr = [];
	for(var i = 0; i < size; i++)
		arr.push(0);
	return arr;
}

function getRatings() {
	con.query("SELECT User, UserCode FROM Users", function(err, result, fields) {
		if(err) {
			console.log("Error: couldn't query database");
			throw err;
		}
		postTitlePosition = {};
		userLabels = [];
		for(var user in result) {
			userLabels.push(user.User);
			con.query("SELECT A.Title, R.Stars FROM Users U\
					   INNER JOIN Ratings R ON U.UserCode = R.UserCode\
					   INNER JOIN Articles A ON A.PostID = R.PostID\
					   WHERE U.UserCode = " + user["UserCode"], function(err, result, fields) {
				currentRatings = arrayOfZeros(matrixWidth);
				for(rating in result) {
					if(!(rating["Title"] in postTitlePosition)) {
						postTitlePosition[rating["Title"]] = width++;
						currentRatings.push(rating["Title"]);
					}
					else
						currentRatings[postTitlePosition[rating["Title"]]] = rating["Stars"];
				}
			});
			recommendInput.push(currentRatings);
		}
	});
}

function buildModel() {
	getRatings();
	var bias = recommender.calculateBias(recommendInput);
	var postTitleLabels = arrayOfZeros(width);
	for(var title in postTitlePosition) {
		postTitleLabels[postTitlePosition[title]] = title; 
	}
	model = recommender.buildModelWithBias(recommendInput, bias, userLabels, postTitleLabels);
}

function saveModel() {
	fs.writeFile("model.json", JSON.stringify(model), function(err) {
		if(err) {
			console.log("Error: model not saved");
			throw err;
		}
		console.log("Model saved succesfully");
	});
}

function loadModel() {
	
}