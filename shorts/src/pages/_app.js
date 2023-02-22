import "@/styles/globals.css";
import Footer from "@/components/Footer";
import Header from "@/components/Header";

export default function App({ Component, pageProps }) {
  return (
    <div className="flex flex-col h-screen">
      <Header />
      <main className="mb-auto grow mt-14">
        <Component {...pageProps} />
      </main>
      <Footer />
    </div>
  );
}
