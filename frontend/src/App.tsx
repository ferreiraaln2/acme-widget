import { useEffect, useMemo, useState } from 'react'
import { fetchBasketTotal, fetchProducts, type Product } from './api'
import './App.css'

type BasketLine = {
  code: string
  quantity: number
}

function App() {
  const [products, setProducts] = useState<Product[]>([])
  const [lines, setLines] = useState<BasketLine[]>([])
  const [total, setTotal] = useState<string>('$0.00')
  const [error, setError] = useState<string | null>(null)
  const [loading, setLoading] = useState(true)

  const itemCodes = useMemo(
    () => lines.flatMap((line) => Array.from({ length: line.quantity }, () => line.code)),
    [lines],
  )

  useEffect(() => {
    fetchProducts()
      .then((data) => {
        setProducts(data)
        setError(null)
      })
      .catch((err: Error) => setError(err.message))
      .finally(() => setLoading(false))
  }, [])

  useEffect(() => {
    if (itemCodes.length === 0) {
      setTotal('$0.00')
      return
    }

    let cancelled = false

    fetchBasketTotal(itemCodes)
      .then((result) => {
        if (!cancelled) {
          setTotal(result.formatted)
          setError(null)
        }
      })
      .catch((err: Error) => {
        if (!cancelled) {
          setError(err.message)
        }
      })

    return () => {
      cancelled = true
    }
  }, [itemCodes])

  function addProduct(code: string) {
    setLines((current) => {
      const existing = current.find((line) => line.code === code)
      if (!existing) {
        return [...current, { code, quantity: 1 }]
      }

      return current.map((line) =>
        line.code === code ? { ...line, quantity: line.quantity + 1 } : line,
      )
    })
  }

  function removeOne(code: string) {
    setLines((current) =>
      current
        .map((line) =>
          line.code === code ? { ...line, quantity: line.quantity - 1 } : line,
        )
        .filter((line) => line.quantity > 0),
    )
  }

  function clearBasket() {
    setLines([])
  }

  return (
    <div className="page">
      <header className="hero">
        <p className="brand">Acme Widget Co</p>
        <h1>Sales basket</h1>
        <p className="lede">Add widgets and see delivery and offers applied live.</p>
      </header>

      <main className="layout">
        <section className="panel" aria-labelledby="catalogue-heading">
          <h2 id="catalogue-heading">Catalogue</h2>
          {loading && <p className="muted">Loading products…</p>}
          <ul className="product-list">
            {products.map((product) => (
              <li key={product.code} className="product-row">
                <div>
                  <strong>{product.name}</strong>
                  <span className="code">{product.code}</span>
                </div>
                <div className="product-actions">
                  <span>{product.formatted_price}</span>
                  <button type="button" onClick={() => addProduct(product.code)}>
                    Add
                  </button>
                </div>
              </li>
            ))}
          </ul>
        </section>

        <section className="panel basket" aria-labelledby="basket-heading">
          <div className="basket-header">
            <h2 id="basket-heading">Basket</h2>
            {lines.length > 0 && (
              <button type="button" className="ghost" onClick={clearBasket}>
                Clear
              </button>
            )}
          </div>

          {lines.length === 0 ? (
            <p className="muted">Your basket is empty.</p>
          ) : (
            <ul className="basket-list">
              {lines.map((line) => {
                const product = products.find((item) => item.code === line.code)
                return (
                  <li key={line.code} className="basket-row">
                    <div>
                      <strong>{product?.name ?? line.code}</strong>
                      <span className="code">× {line.quantity}</span>
                    </div>
                    <button type="button" className="ghost" onClick={() => removeOne(line.code)}>
                      Remove
                    </button>
                  </li>
                )
              })}
            </ul>
          )}

          <div className="total" data-testid="basket-total">
            <span>Total</span>
            <strong>{total}</strong>
          </div>
        </section>
      </main>

      {error && <p className="error" role="alert">{error}</p>}
    </div>
  )
}

export default App
