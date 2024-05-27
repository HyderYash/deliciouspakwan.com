import React, { useEffect, useState } from "react";
import { TableRow, TableCell, Select, MenuItem, Button, Table, TableHead, TableBody, Box, Paper, TableContainer, CircularProgress, FormControl, InputLabel } from "@mui/material";
import Navbar from "./components/Navbar";
import DeleteIcon from "@mui/icons-material/Delete";
import SaveIcon from "@mui/icons-material/Save";
import ShareIcon from "@mui/icons-material/Share";
import fetchAPIData from "fetch-api-data";
import CheckCircleIcon from "@mui/icons-material/CheckCircle";

var API_ROOT_PATH = "";
if (process.env.NODE_ENV === "production") {
  API_ROOT_PATH = "http://www.deliciouspakwan.com";
} else {
  API_ROOT_PATH = "http://local.deliciouspakwan.com";
}
const ItemRow = ({ items, selectedRowItem, brands, selectedRowBrand, quantities, selectedRowQnt, units, selectedRowUnit, onRowChange, onRowSelect, isItemChecked, needItemDropdown, itemName }) => {
  const [selectedItem, setSelectedItem] = useState(selectedRowItem);
  const [selectedBrand, setSelectedBrand] = useState(selectedRowBrand);
  const [selectedQuantity, setSelectedQuantity] = useState(selectedRowQnt);
  const [selectedUnit, setSelectedUnit] = useState(selectedRowUnit);
  const handleItemChange = (event) => {
    setSelectedItem(event.target.value);
    onRowChange({ item: event.target.value }); // Propagate change
  };

  const handleBrandChange = (event) => {
    setSelectedBrand(event.target.value);
    onRowChange({ brand: event.target.value }); // Propagate change
  };

  const handleQuantityChange = (event) => {
    setSelectedQuantity(event.target.value);
    onRowChange({ quantity: event.target.value }); // Propagate change
  };

  const handleUnitChange = (event) => {
    setSelectedUnit(event.target.value);
    onRowChange({ unit: event.target.value }); // Propagate change
  };

  return (
    <TableRow>
      <TableCell>
        {needItemDropdown ? (
          <Select value={selectedItem} onChange={handleItemChange} size="small" style={{ maxWidth: 100 }}>
            {items.map((item) => (
              <MenuItem key={item.ID} value={item.ID}>
                {item.item_name}
              </MenuItem>
            ))}
          </Select>
        ) : (
          itemName
        )}
      </TableCell>
      <TableCell>
        <Select value={selectedBrand} onChange={handleBrandChange} size="small" style={{ maxWidth: 100 }}>
          {brands.map((brand) => (
            <MenuItem key={brand.ID} value={brand.ID}>
              {brand.brand_name}
            </MenuItem>
          ))}
        </Select>
      </TableCell>
      <TableCell style={{ display: "flex", justifyContent: "space-evenly" }}>
        <Select value={selectedQuantity} onChange={handleQuantityChange} size="small" style={{ maxWidth: 70 }}>
          {quantities.map((quantity) => (
            <MenuItem key={quantity} value={quantity}>
              {quantity}
            </MenuItem>
          ))}
        </Select>
        <Select value={selectedUnit} onChange={handleUnitChange} size="small" style={{ maxWidth: 70 }}>
          {units.map((unit) => (
            <MenuItem key={unit.ID} value={unit.ID}>
              {unit.unit_name}
            </MenuItem>
          ))}
        </Select>
      </TableCell>
      <TableCell style={{ justifyContent: "center", alignItems: "center" }}>{selectedItem !== "" && selectedBrand !== "" && selectedQuantity !== "" && selectedUnit !== "" ? <CheckCircleIcon color="success" /> : null}</TableCell>
    </TableRow>
  );
};

const MyComponent = () => {
  const [itemList, setItemList] = useState([]);
  const [items, setItems] = useState([]);
  const [brands, setBrands] = useState([]);
  const [units, setUnits] = useState([]);
  const [loading, setLoading] = useState(false);
  const [dateCatList, setDateCatList] = useState([]);
  const [selectedDateCatList, setSelectedDateCatList] = useState("");
  const [needItemDropdown, setNeedItemDropdown] = useState(false);
  const handleSave = () => {
    setLoading(true);
    const selectedRows = itemList;
    const filteredObjects = selectedRows.filter((obj) => {
      const relevantProps = ["brand", "item", "unit"]; // Array of properties to check
      return relevantProps.every((prop) => Boolean(obj[prop]));
    });

    const formData = filteredObjects;
    fetchAPIData(`${API_ROOT_PATH}/api/rasan/save_rasan_list.php`, formData, "POST").then((json) => {
      window.location.reload();
      setLoading(false);
    });
  };

  const handleRowChange = (rowIndex, changes) => {
    setItemList((prevList) => {
      const updatedList = [...prevList];
      updatedList[rowIndex] = { ...updatedList[rowIndex], ...changes };
      return updatedList;
    });
  };

  const handleRowSelect = (rowIndex, checked) => {
    setItemList((prevList) => {
      const updatedList = [...prevList];
      updatedList[rowIndex].isChecked = checked;
      return updatedList;
    });
  };

  const addNewRow = () => {
    const updatedList = [...itemList];
    updatedList.push({ item: "", brand: "", quantity: 1, isChecked: false, unit: "", itemName: "" });
    setItemList(updatedList);
  };

  const getItemsBrandList = (LIST_NAME) => {
    return new Promise((resolve, reject) => {
      setLoading(true);
      if (LIST_NAME === "") {
        setNeedItemDropdown(false);
      } else {
        setNeedItemDropdown(true);
      }
      const formData = {
        LIST_NAME,
      };

      fetchAPIData(`${API_ROOT_PATH}/api/rasan/rasan_list.php`, formData, "POST")
        .then((json) => {
          resolve(json.records);
        })
        .catch((error) => {
          reject(error);
        });
    });
  };

  useEffect(() => {
    getItemsBrandList("").then((list) => {
      let arr = [];
      for (let i of list.item_list) {
        arr.push({ item: i.ID, brand: "", quantity: 1, isChecked: false, unit: "", itemName: i.item_name });
      }
      setItems(list.item_list);
      setBrands(list.brand_list);
      setUnits(list.unit_list);
      let dcl = list.datecat_list;
      dcl.unshift({ date_cat: "Blank/New" });
      setDateCatList(list.datecat_list);
      setItemList(arr);
      setLoading(false);
    });
  }, []);

  const getDateCatResult = async (e) => {
    setSelectedDateCatList(e.target.value);
    let list = await getItemsBrandList(e.target.value);
    let arr = [];
    if (e.target.value === "") {
      for (let i of list.item_list) {
        arr.push({ item: i.ID, brand: "", quantity: 1, isChecked: false, unit: "", itemName: i.item_name });
      }
    } else {
      for (let i of list) {
        arr.push({ item: i.item_id, brand: i.brand_id, quantity: i.qnt, isChecked: true, unit: i.unit_id, itemName: i.item_name });
      }
    }
    setItemList(arr);
    setLoading(false);
  };

  return (
    <div className="table-container">
      {loading ? (
        <div style={{ display: "flex", justifyContent: "center", alignItems: "center", height: 100 }}>
          <CircularProgress color="success" />
        </div>
      ) : (
        <>
          <Box sx={{ display: "flex", justifyContent: "space-between", padding: 1 }}>
            <FormControl size="small" style={{ width: "50%" }}>
              <InputLabel id="demo-simple-select-label">Select</InputLabel>
              <Select value={selectedDateCatList} onChange={getDateCatResult} size="small" label="Select">
                {dateCatList.map((da) => (
                  <MenuItem key={da.date_cat} value={da.date_cat !== "Blank/New" ? da.date_cat : ""}>
                    {da.date_cat}
                  </MenuItem>
                ))}
              </Select>
            </FormControl>
            {needItemDropdown ? (
              <Button variant="contained" color="success" onClick={addNewRow}>
                New
              </Button>
            ) : null}
          </Box>
          {itemList.length > 0 ? (
            <div style={{ display: "flex", justifyContent: "space-evenly", alignItems: "center", marginTop: 10, marginBottom: 10 }}>
              <Button variant="contained" color="error" startIcon={<DeleteIcon />} onClick={() => window.location.reload()}>
                Cancel
              </Button>
              <Button variant="contained" color="success" endIcon={<SaveIcon />} onClick={handleSave}>
                Save
              </Button>
              <Button variant="contained" endIcon={<ShareIcon />}>
                Send
              </Button>
            </div>
          ) : null}
          <TableContainer component={Paper} style={{ overflow: "hidden" }}>
            <Table size="small">
              <TableHead>
                <TableRow>
                  <TableCell>Item</TableCell>
                  <TableCell>Brand</TableCell>
                  <TableCell>Quantity</TableCell>
                  <TableCell></TableCell>
                </TableRow>
              </TableHead>
              <TableBody>
                {itemList.map((row, rowIndex) => (
                  <ItemRow
                    key={rowIndex}
                    items={items} // Replace with dynamic items
                    brands={brands} // Replace with dynamic brands
                    quantities={[1, 2, 3, 4, 5, 6, 7, 8, 9, 10]} // Replace with dynamic quantities
                    units={units}
                    onRowChange={(changes) => handleRowChange(rowIndex, changes)}
                    onRowSelect={(checked) => handleRowSelect(rowIndex, checked)}
                    isItemChecked={row.isChecked}
                    selectedRowItem={row.item}
                    selectedRowBrand={row.brand}
                    selectedRowQnt={row.quantity}
                    selectedRowUnit={row.unit}
                    needItemDropdown={needItemDropdown}
                    itemName={row.itemName}
                  />
                ))}
              </TableBody>
            </Table>
          </TableContainer>
        </>
      )}
    </div>
  );
};
// Add CSS styles for responsiveness
const styles = `

  @media (max-width: 768px) {
    table {
      display: block;
      width: 100%;
    }
    thead {
      display: none;
    }
    tr {
      display: block;
      margin-bottom: 1rem;
    }
    th {
      display: block;
      text-align: right;
      padding-bottom: 5px;
      font-weight: bold;
    }
    td {
      padding: 5px !important;
    }
  }
`;

// Inject styles into the component (consider using a CSS library for better management)
const StyledMyComponent = () => {
  const [isStyleInjected, setIsStyleInjected] = useState(false);

  if (!isStyleInjected) {
    const styleEl = document.createElement("style");
    styleEl.innerHTML = styles;
    document.head.appendChild(styleEl);
    setIsStyleInjected(true);
  }

  return (
    <>
      <Navbar />
      <MyComponent />
    </>
  );
};

export default StyledMyComponent;
