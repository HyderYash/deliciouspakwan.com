import React from "react";
import ReactDOM from "react-dom/client";
import "./index.css";
import App from "./App";
import Header from "../src/components/Header";
import Footer from "../src/components/Footer";

const root = ReactDOM.createRoot(document.getElementById("root"));
root.render(
  <div className="flex flex-col h-screen">
    <Header />
    <main className="mb-auto grow mt-14">
      <App />
    </main>
    <Footer />
  </div>
);
