import React, { useState } from "react";
import NavBarMenu from "./NavBarMenu";
import { Button, Card, Container, Form, FormControl } from "react-bootstrap";
import { fetchAPIData, FOOD_API_KEY } from "./Common";
import Message from "../assets/utility/Message";
import Spinner from "../assets/utility/Loader";

export default function AddNutrition() {
  const [query, setQuery] = useState("");
  const [message, setMessage] = useState("");
  const [loading, setLoading] = useState(false);
  const handleSubmit = (e) => {
    e.preventDefault();
    const formData = {
      FOOD_NAME: query,
    };
    fetchAPIData("/api/nutrient/checkNutrientInDB.php", formData)
      .then((res) => {
        setLoading(true);
        setMessage("Calling The API...");
        nurientAPICALL(res.FoodId);
      })
      .catch((err) => {
        console.error(err);
      });
  };
  const nurientAPICALL = (FoodId) => {
    const FINAL_QUERY = query.toLowerCase();
    fetchAPIData(
      `https://api.nal.usda.gov/fdc/v1/foods/search?query=${FINAL_QUERY}&dataType=Branded&pageSize=1&pageNumber=1&sortBy=publishedDate&sortOrder=asc&api_key=${FOOD_API_KEY}`,
      "",
      "GET",
      false,
      "External"
    )
      .then((res) => {
        setMessage("Updating the Database...");
        sendAPIDataToPHPAPI(
          res.foods[0].fdcId,
          res.foods[0].foodNutrients,
          FoodId
        );
      })
      .catch((err) => {
        console.error(err);
      });
  };
  const sendAPIDataToPHPAPI = (fdcID, foodNutrients, FoodId) => {
    const formData = {
      FDC_ID: fdcID,
      FOOD_NUTRIENTS_ARR: foodNutrients,
      FOOD_ID: FoodId,
    };
    fetchAPIData(
      "/api/nutrient/updateNutrientInDB.php",
      formData,
      "POST",
      false,
      "Internal"
    )
      .then((res) => {
        setLoading(false);
        setMessage("Process Completed...");
        setTimeout(() => {
          setMessage("");
        }, 3000);
      })
      .catch((err) => {
        console.error(err);
      });
  };
  return (
    <div>
      <NavBarMenu />
      {loading === true ? (
        <Spinner />
      ) : (
        <Container>
          <Card
            className="mx-auto mt-5 mb-5"
            style={{
              background: "azure",
              width: "auto",
              borderRadius: "1rem",
            }}
          >
            <Card.Body>
              <Card.Title>
                <h2>Enter Search Query</h2>
              </Card.Title>
              {message !== "" ? <Message message={message} /> : null}
              <Form onSubmit={handleSubmit}>
                <FormControl
                  type="text"
                  placeholder="Enter Nutrient"
                  required
                  autoComplete="off"
                  onChange={(e) => setQuery(e.target.value)}
                  className="mr-sm-2 mt-3"
                />
                <div className="mt-3">
                  <Button className="mx-auto" type="submit" variant="primary">
                    Submit
                  </Button>
                </div>
              </Form>
            </Card.Body>
          </Card>
        </Container>
      )}
    </div>
  );
}
