import express from "express";
import bodyParser from "body-parser";
import { dirname } from "path";
import { fileURLToPath } from "url";
import axios from "axios";
import fs from "fs";
import path from "path";
import ejs from "ejs";

const __dirname = dirname(fileURLToPath(import.meta.url));
const app = express();
const port = 3000;
const directoryPath = path.join(__dirname, "/public/images/banners");
const API_URL = "https://pinnacle-odds.p.rapidapi.com/kit/v1/";
const apiKey = "0de5dc4831msh86c867889e7c4c3p166e2djsndfa8877c8fac";
const config = {
  params: {sport_id: '1'},
  headers: {
    'x-rapidapi-key': '0de5dc4831msh86c867889e7c4c3p166e2djsndfa8877c8fac',
    'x-rapidapi-host': 'pinnacle-odds.p.rapidapi.com'
  }
};

// { content: JSON.stringify(result.data) }
const sports = 'sports'
const leagues = 'leagues'


app.set("view engine", "ejs");
app.use(express.static("public"));
app.use(bodyParser.urlencoded({ extended: true }));

// Middleware to set MIME type for CSS files
app.use(express.static("public"));

app.get("/", async (req, res) => {
  try {
    // const response = await axios.get(`${API_URL}meta-periods`, config);
    // console.log(response.data)
    res.render("index.ejs");
  } catch (error) {
    console.error("Error fetching leagues:", error);
    res.status(500).json({ message: "Error fetching leagues" });
  }
});

// Route to render the edit page
app.get("/odds", (req, res) => {
  res.render("odds.ejs");
});

app.get("/responsible", (req, res) => {
  res.render("responsible_gaming.ejs");
});

app.get("/bet-list", (req, res) => {
  res.render("bet-list.ejs");
});

app.get("/options", (req, res) => {
  res.render("options.ejs");
});

app.get("/login", (req, res) => {
  res.render("log-in.ejs");
});

app.get("/outright", (req, res) => {
  res.render("outright.ejs");
});

app.get("/tizer", (req, res) => {
  res.render("tizer.ejs");
});

app.get("/live", (req, res) => {
  res.render("live.ejs");
});

app.get("/chance", (req, res) => {
  res.render("chance.ejs");
});

app.get("/tc", (req, res) => {
  res.render("T&C.ejs");
});

app.get("/change-pass", (req, res) => {
  res.render("change-pass.ejs");
});

app.get("/support", (req, res) => {
  res.render("support.ejs");
});

app.get("/super-agent", (req, res) => {
  res.render("super-agent.ejs");
});

app.get("/404", (req, res) => {
  res.render("404.ejs");
});

app.listen(port, () => {
  console.log(`Backend server is running on http://localhost:${port}`);
});
