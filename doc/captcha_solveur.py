from playwright.sync_api import sync_playwright
import time

MON_JWT = "eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJuYW1lIjoiYXNoIiwiZW1haWwiOiIiLCJzZWNyZXQiOiJiMmQ1MTgwODY1MzYxYzg1NmVjZWI0MjRlODIxZDE4ZTU4YzI5NmZkNjRmYzhmZjE1OWVmZTVjNGFjMGMxOTczIiwibGV2ZWwiOjEsInRpdGFuaWNfZGVhZGxpbmUiOjE3NzMwNzA1MTcsInRpdGFuaWMiOmZhbHNlfQ.1O8MaMPAfOl1LVCcHMQlOfGwGjXK9eOGTU5S-H2_1YE"

def solve():
    with sync_playwright() as p:
        print("--- Démarrage de l'automatisation ---")
        browser = p.chromium.launch(headless=False) # On garde la fenêtre ouverte pour voir
        context = browser.new_context()
        
        # Injection du cookie
        context.add_cookies([{
            'name': 'JWT',
            'value': MON_JWT,
            'domain': 'challenge.zimple.eu',
            'path': '/'
        }])
        
        page = context.new_page()
        page.goto("http://challenge.zimple.eu/captcha.php")
        
        print("Attente du captcha (10s max)...")
        
        try:
            # On cherche TOUS les noms de boutons possibles (classes CSS)
            # .slider-button, .slider_button, .handler, .slider-handler
            slider_selector = ".slider_button, .slider-button, .handler, .slider-handler, #slider-button"
            
            # On attend que l'élément soit là
            page.wait_for_selector(slider_selector, timeout=10000)
            slider = page.query_selector(slider_selector)
            
            if slider:
                print("Bouton trouvé ! Préparation du mouvement...")
                box = slider.bounding_box()
                
                # On déplace la souris au centre du bouton
                page.mouse.move(box["x"] + box["width"] / 2, box["y"] + box["height"] / 2)
                page.mouse.down()
                
                # On glisse vers la droite. Essayons 180 pixels pour commencer.
                # 'steps' simule un mouvement humain (plus lent)
                page.mouse.move(box["x"] + 180, box["y"] + box["height"] / 2, steps=30)
                page.mouse.up()
                
                print("Glissement terminé. Vérification du résultat...")
                time.sleep(10) # On attend pour voir si le drapeau apparaît
            
        except Exception as e:
            print("\nERREUR : Le bouton est introuvable.")
            print("Voici les éléments 'boutons' détectés sur la page :")
            # Ce petit code va lister tous les boutons pour nous aider à trouver le bon nom
            page.evaluate("() => console.log(Array.from(document.querySelectorAll('div')).map(d => d.className))")
            time.sleep(5)

        browser.close()

if __name__ == "__main__":
    solve()