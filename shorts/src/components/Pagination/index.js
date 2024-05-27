// import React from "react";

// const index = ({
//   displayPageNumbers,
//   currentPageNum,
//   setCurrentPageNum,
//   totalNumPages,
// }) => {
//   return (
//     <nav className="mt-5 mb-2 flex justify-center items-center">
//       <ul className="inline-flex -space-x-px">
//         <li>
//           <span
//             onClick={() => {
//               if (currentPageNum > 1) {
//                 sessionStorage.setItem("currPageNum", currentPageNum - 1);
//                 setCurrentPageNum(currentPageNum - 1);
//               }
//             }}
//             className="px-3 py-2 ml-0 leading-tight border hover:text-white bg-gray-900 border-gray-700 text-gray-400 cursor-pointer font-bold"
//           >
//             Prev
//           </span>
//         </li>
//         {displayPageNumbers.map((x) => {
//           return (
//             <li key={x}>
//               <span
//                 onClick={() => {
//                   sessionStorage.setItem("currPageNum", x);
//                   setCurrentPageNum(x);
//                 }}
//                 className={`px-3 py-2 leading-tight border border-gray-700  cursor-pointer font-bold ${
//                   currentPageNum === x
//                     ? "bg-gray-300 text-gray-900 text-lg"
//                     : "bg-gray-900 text-gray-400"
//                 }`}
//               >
//                 {x}
//               </span>
//             </li>
//           );
//         })}

//         <li>
//           <span
//             onClick={() => {
//               if (currentPageNum < totalNumPages) {
//                 sessionStorage.setItem("currPageNum", currentPageNum + 1);
//                 setCurrentPageNum(currentPageNum + 1);
//               }
//             }}
//             className="px-3 py-2 ml-0 leading-tight border hover:text-white bg-gray-900 border-gray-700 text-gray-400 cursor-pointer font-bold"
//           >
//             Next
//           </span>
//         </li>
//       </ul>
//     </nav>
//   );
// };

// export default index;

import React from "react";

const index = ({
  displayPageNumbers,
  currentPageNum,
  setCurrentPageNum,
  totalNumPages,
  maxDisplayPages = 6, // Maximum number of page numbers to display
}) => {
  const handlePrevClick = () => {
    if (currentPageNum > 1) {
      sessionStorage.setItem("currPageNum", currentPageNum - 1);
      setCurrentPageNum(currentPageNum - 1);
    }
  };

  const handleNextClick = () => {
    if (currentPageNum < totalNumPages) {
      sessionStorage.setItem("currPageNum", currentPageNum + 1);
      setCurrentPageNum(currentPageNum + 1);
    }
  };

  // Calculate the start and end indices for the sliding window
  let start = Math.max(currentPageNum - Math.floor(maxDisplayPages / 2), 1);
  let end = start + maxDisplayPages - 1;

  if (end > totalNumPages) {
    end = totalNumPages;
    start = Math.max(end - maxDisplayPages + 1, 1);
  }

  // Create the sliding window page numbers
  const pageNumbersToDisplay = [];
  for (let i = start; i <= end; i++) {
    pageNumbersToDisplay.push(i);
  }

  return (
    <nav className="mt-5 mb-2 flex justify-center items-center">
      <ul className="inline-flex -space-x-px">
        <li>
          <span
            onClick={handlePrevClick}
            className="px-3 py-2 ml-0 leading-tight border hover:text-white bg-gray-900 border-gray-700 text-gray-400 cursor-pointer font-bold"
          >
            Prev
          </span>
        </li>
        {pageNumbersToDisplay.map((x) => {
          return (
            <li key={x}>
              <span
                onClick={() => {
                  sessionStorage.setItem("currPageNum", x);
                  setCurrentPageNum(x);
                }}
                className={`px-3 py-2 leading-tight border border-gray-700 cursor-pointer font-bold ${
                  currentPageNum === x
                    ? "bg-gray-300 text-gray-900 text-lg"
                    : "bg-gray-900 text-gray-400"
                }`}
              >
                {x}
              </span>
            </li>
          );
        })}
        <li>
          <span
            onClick={handleNextClick}
            className="px-3 py-2 ml-0 leading-tight border hover:text-white bg-gray-900 border-gray-700 text-gray-400 cursor-pointer font-bold"
          >
            Next
          </span>
        </li>
      </ul>
    </nav>
  );
};

export default index;
