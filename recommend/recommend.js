var ratingInput;
var ratingModel;
var currentRatings;

var ratingModel {
	model: {},
	name: "rating_model"
};

var similarPeopleModel {

};

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

function matrixOfZeros(width, height) {
	var matrix = [];
	for(var i = 0; i < height; i++) {
		var arr = [];
		for(var j = 0; j < width; j++)
			arr.push(0);
		matrix.push(arr);
	}
	return matrix;
}

function buildRatingModel() {
	con.query("SELECT COUNT(A.*), COUNT(U.*) FROM Articles A INNER JOIN Users U"), function(err, result, fields) {
		ratingInput = matrixOfZeros(result[0]["COUNT(A.*)"], result[0]["COUNT(U.*)"]);
		con.query("SELECT U.UserName A.Title, R.Stars FROM Users U\
				   INNER JOIN Ratings R ON U.UserCode = R.UserCode\
				   INNER JOIN Articles A ON A.PostID = R.PostID", function(err, result, fields) {
			var postTitlePosition = {};
			var userPosition = {};
			var width = 0, height = 0;
			for(var rating in result) {
				if(!(rating["Title"] in postTitlePosition))
					postTitlePosition[rating["Title"]] = width++;
				if(!(rating["UserName"] in userPosition))
					userPosition[rating["UserName"]] = height++;
				ratingInput[userPosition[rating["UserName"]]][postTitlePosition[rating["Title"]]] = rating["Stars"];
			}
			var bias = recommender.calculateBias(ratingInput);
			var postTitleLabels = [], userLabels = [];
			for(var title in postTitlePosition)
				postTitleLabels[postTitlePosition[title]] = title; 
			for(var userName in userPosition)
				userLabels[userPosition[userName]] = userName;
			ratingModel = {
				model: recommender.buildModelWithBias(ratingInput, bias, userLabels, postTitleLabels);
				name: "rating_model"
			}
	});
}

function saveModel(model) {
	fs.writeFile(model.name + ".json", JSON.stringify(model.model), function(err) {
		if(err) {
			console.log("Error: model not saved");
			throw err;
		}
		console.log("Model saved succesfully");
	});
}

function loadModel(name) {
	
}