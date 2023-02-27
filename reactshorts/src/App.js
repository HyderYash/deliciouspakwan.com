import { BrowserRouter, Route, Switch } from "react-router-dom";
import ShortsViewer from "./pages/ShortsViewer";
import NotFoundPage from "./pages/NotFoundPage";

export default function App() {
  return (
    <BrowserRouter>
      <Switch>
        <Route path="/" exact>
          <Home />
        </Route>
        <Route exact path="/shortsviewer/:id" render={() => <ShortsViewer />} />

        <Route path="*" element={<NotFoundPage />} />
      </Switch>
    </BrowserRouter>
  );
}
