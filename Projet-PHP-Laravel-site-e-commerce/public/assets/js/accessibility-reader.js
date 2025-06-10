/**
 * Lecteur d'Accessibilité - Synthèse vocale pour l'accessibilité web
 * Lit automatiquement le contenu des éléments survolés par la souris
 */

class AccessibilityReader {
    constructor() {
      this.isActive = false
      this.synth = window.speechSynthesis
      this.currentUtterance = null
      this.currentElement = null
      this.readingIndicator = null
      this.tooltip = null
  
      this.init()
    }
  
    /**
     * Initialise le lecteur d'accessibilité
     */
    init() {
      // Vérifier la compatibilité du navigateur
      if (!this.checkBrowserCompatibility()) {
        return
      }
  
      // Initialiser les éléments DOM
      this.initializeElements()
  
      // Configurer les événements
      this.setupEventListeners()
  
      console.log("Lecteur d'accessibilité initialisé")
    }
  
    /**
     * Vérifie la compatibilité du navigateur
     */
    checkBrowserCompatibility() {
      if (!("speechSynthesis" in window)) {
        console.warn("La synthèse vocale n'est pas supportée par ce navigateur.")
        alert(
          "Votre navigateur ne supporte pas la synthèse vocale. Veuillez utiliser un navigateur moderne comme Chrome, Firefox ou Safari.",
        )
        return false
      }
      return true
    }
  
    /**
     * Initialise les éléments DOM nécessaires
     */
    initializeElements() {
      this.readingIndicator = document.getElementById("readingIndicator")
      this.tooltip = document.getElementById("tooltip")
  
      // Créer les éléments s'ils n'existent pas
      if (!this.readingIndicator) {
        this.readingIndicator = this.createElement("div", {
          id: "readingIndicator",
          className: "reading-indicator",
        })
        document.body.appendChild(this.readingIndicator)
      }
  
      if (!this.tooltip) {
        this.tooltip = this.createElement("div", {
          id: "tooltip",
          className: "tooltip",
        })
        document.body.appendChild(this.tooltip)
      }
    }
  
    /**
     * Crée un élément DOM avec les propriétés spécifiées
     */
    createElement(tag, properties) {
      const element = document.createElement(tag)
      Object.assign(element, properties)
      return element
    }
  
    /**
     * Configure tous les événements nécessaires
     */
    setupEventListeners() {
      const btn = document.getElementById("accessibilityReaderBtn")
      if (btn) {
        btn.addEventListener("click", () => this.toggle())
      }
  
      // Gestion des événements de survol
      document.addEventListener("mouseover", (e) => this.handleMouseOver(e))
      document.addEventListener("mouseout", (e) => this.handleMouseOut(e))
  
      // Arrêter la lecture si on clique ailleurs
      document.addEventListener("click", (e) => {
        if (e.target.id !== "accessibilityReaderBtn") {
          this.stopReading()
        }
      })
  
      // Gestion du tooltip
      document.addEventListener("mousemove", (e) => this.updateTooltip(e))
  
      // Raccourcis clavier
      document.addEventListener("keydown", (e) => this.handleKeyboard(e))
    }
  
    /**
     * Active/désactive le lecteur d'accessibilité
     */
    toggle() {
      this.isActive = !this.isActive
      const btn = document.getElementById("accessibilityReaderBtn")
      const icon = btn?.querySelector("i")
  
      if (this.isActive) {
        this.activate(btn, icon)
      } else {
        this.deactivate(btn, icon)
      }
    }
  
    /**
     * Active le lecteur
     */
    activate(btn, icon) {
      btn?.classList.add("active")
      if (icon) icon.className = "fas fa-volume-down"
      if (btn) btn.title = "Désactiver la lecture vocale (Actif)"
  
      const message = "Lecture vocale activée. Survolez les éléments pour les entendre."
      this.showReadingIndicator(message)
      this.speak(message)
    }
  
    /**
     * Désactive le lecteur
     */
    deactivate(btn, icon) {
      btn?.classList.remove("active")
      if (icon) icon.className = "fas fa-volume-up"
      if (btn) btn.title = "Activer la lecture vocale"
  
      this.hideReadingIndicator()
      this.stopReading()
    }
  
    /**
     * Gère les événements de survol de souris
     */
    handleMouseOver(e) {
      if (!this.isActive) return
  
      const element = e.target
      if (element === this.currentElement) return
  
      this.stopReading()
      this.currentElement = element
  
      const textToRead = this.getTextToRead(element)
      if (textToRead) {
        this.highlightElement(element)
        this.speak(textToRead)
        this.showReadingIndicator(textToRead)
      }
    }
  
    /**
     * Gère les événements de sortie de survol
     */
    handleMouseOut(e) {
      if (!this.isActive) return
  
      const element = e.target
      this.removeHighlight(element)
    }
  
    /**
     * Gère les raccourcis clavier
     */
    handleKeyboard(e) {
      // Ctrl + Alt + A pour activer/désactiver
      if (e.ctrlKey && e.altKey && e.key === "a") {
        e.preventDefault()
        this.toggle()
      }
  
      // Échap pour arrêter la lecture
      if (e.key === "Escape" && this.isActive) {
        this.stopReading()
      }
    }
  
    /**
     * Extrait le texte à lire d'un élément
     */
    getTextToRead(element) {
      // Priorité aux attributs data-text personnalisés
      if (element.dataset.text) {
        return element.dataset.text
      }
  
      // Attributs d'accessibilité standard
      const accessibilityText = this.getAccessibilityText(element)
      if (accessibilityText) {
        return accessibilityText
      }
  
      // Contenu textuel selon le type d'élément
      const elementText = this.getElementSpecificText(element)
  
      // Nettoyer et limiter le texte
      if (elementText) {
        let cleanText = elementText.replace(/\s+/g, " ").trim()
        if (cleanText.length > 200) {
          cleanText = cleanText.substring(0, 200) + "..."
        }
        return cleanText
      }
  
      return null
    }
  
    /**
     * Récupère le texte d'accessibilité standard
     */
    getAccessibilityText(element) {
      return element.getAttribute("aria-label") || element.getAttribute("title") || element.getAttribute("alt")
    }
  
    /**
     * Récupère le texte spécifique selon le type d'élément
     */
    getElementSpecificText(element) {
      const tagName = element.tagName
  
      switch (tagName) {
        case "IMG":
          return element.alt || "Image sans description"
  
        case "A":
          return `Lien: ${element.textContent.trim()}`
  
        case "BUTTON":
          return `Bouton: ${element.textContent.trim()}`
  
        case "INPUT":
          return this.getInputText(element)
  
        case "SELECT":
          return `Liste déroulante: ${element.options[element.selectedIndex]?.text || "Sélectionner une option"}`
  
        case "TEXTAREA":
          return `Zone de texte: ${element.placeholder || element.value || "Zone de saisie de texte"}`
  
        case "LI":
          return `Élément de liste: ${element.textContent.trim()}`
  
        default:
          if (["H1", "H2", "H3", "H4", "H5", "H6"].includes(tagName)) {
            return `Titre: ${element.textContent.trim()}`
          }
  
          if (element.textContent && element.textContent.trim()) {
            return element.textContent.trim()
          }
  
          return null
      }
    }
  
    /**
     * Récupère le texte pour les éléments input
     */
    getInputText(element) {
      const type = element.type
  
      switch (type) {
        case "submit":
        case "button":
          return `Bouton: ${element.value || "Envoyer"}`
        case "checkbox":
          return `Case à cocher: ${element.checked ? "cochée" : "non cochée"} ${element.labels?.[0]?.textContent || ""}`
        case "radio":
          return `Bouton radio: ${element.checked ? "sélectionné" : "non sélectionné"} ${element.labels?.[0]?.textContent || ""}`
        default:
          return `Champ ${type}: ${element.placeholder || element.value || "Champ de saisie"}`
      }
    }
  
    /**
     * Lance la synthèse vocale
     */
    speak(text) {
      if (!text) return
  
      this.stopReading()
  
      this.currentUtterance = new SpeechSynthesisUtterance(text)
      this.currentUtterance.lang = "fr-FR"
      this.currentUtterance.rate = 0.9
      this.currentUtterance.pitch = 1
      this.currentUtterance.volume = 0.8
  
      this.currentUtterance.onend = () => {
        this.currentUtterance = null
      }
  
      this.currentUtterance.onerror = (event) => {
        console.error("Erreur de synthèse vocale:", event.error)
        this.currentUtterance = null
      }
  
      this.synth.speak(this.currentUtterance)
    }
  
    /**
     * Arrête la lecture en cours
     */
    stopReading() {
      if (this.synth.speaking) {
        this.synth.cancel()
      }
      this.currentUtterance = null
    }
  
    /**
     * Met en surbrillance l'élément en cours de lecture
     */
    highlightElement(element) {
      element.classList.add("being-read")
    }
  
    /**
     * Supprime la surbrillance de l'élément
     */
    removeHighlight(element) {
      element.classList.remove("being-read")
    }
  
    /**
     * Affiche l'indicateur de lecture
     */
    showReadingIndicator(text) {
      if (this.readingIndicator) {
        this.readingIndicator.textContent = text
        this.readingIndicator.style.display = "block"
      }
    }
  
    /**
     * Cache l'indicateur de lecture
     */
    hideReadingIndicator() {
      if (this.readingIndicator) {
        this.readingIndicator.style.display = "none"
      }
    }
  
    /**
     * Met à jour la position du tooltip
     */
    updateTooltip(e) {
      if (!this.isActive || !this.tooltip) {
        if (this.tooltip) this.tooltip.style.display = "none"
        return
      }
  
      const element = e.target
      const textToRead = this.getTextToRead(element)
  
      if (textToRead && textToRead.length > 50) {
        const displayText = textToRead.substring(0, 100) + (textToRead.length > 100 ? "..." : "")
        this.tooltip.textContent = displayText
        this.tooltip.style.display = "block"
        this.tooltip.style.left = e.pageX + 10 + "px"
        this.tooltip.style.top = e.pageY - 30 + "px"
      } else {
        this.tooltip.style.display = "none"
      }
    }
  }
  
  // Initialiser le lecteur d'accessibilité quand le DOM est prêt
  document.addEventListener("DOMContentLoaded", () => {
    window.accessibilityReader = new AccessibilityReader()
  })
  
  // Exposer la classe globalement pour un accès externe si nécessaire
  window.AccessibilityReader = AccessibilityReader