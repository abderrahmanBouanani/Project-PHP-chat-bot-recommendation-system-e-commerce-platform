document.addEventListener("DOMContentLoaded", () => {
  class SimpleCarousel {
    constructor() {
      this.track = document.getElementById("simpleCarouselTrack")
      this.prevBtn = document.getElementById("simplePrevBtn")
      this.nextBtn = document.getElementById("simpleNextBtn")
      this.dots = document.querySelectorAll(".simple-dot")
      this.cards = document.querySelectorAll(".simple-product-card")

      this.currentSlide = 0
      this.cardsPerView = this.getCardsPerView()
      this.totalSlides = Math.ceil(this.cards.length / this.cardsPerView) - 1
      this.isAnimating = false

      this.init()
    }

    getCardsPerView() {
      if (window.innerWidth <= 480) return 1
      if (window.innerWidth <= 768) return 2
      if (window.innerWidth <= 1200) return 3
      return 4
    }

    init() {
      this.updateCarousel()
      this.bindEvents()
      this.startAutoPlay()
      this.addTouchSupport()
    }

    bindEvents() {
      this.prevBtn.addEventListener("click", () => this.prevSlide())
      this.nextBtn.addEventListener("click", () => this.nextSlide())

      this.dots.forEach((dot, index) => {
        dot.addEventListener("click", () => this.goToSlide(index))
      })

      // Pause auto-play on hover
      this.track.addEventListener("mouseenter", () => this.stopAutoPlay())
      this.track.addEventListener("mouseleave", () => this.startAutoPlay())

      // Responsive
      window.addEventListener("resize", () => {
        this.cardsPerView = this.getCardsPerView()
        this.totalSlides = Math.ceil(this.cards.length / this.cardsPerView) - 1
        this.updateCarousel()
      })

      // Keyboard navigation
      document.addEventListener("keydown", (e) => {
        if (e.key === "ArrowLeft") this.prevSlide()
        if (e.key === "ArrowRight") this.nextSlide()
      })
    }

    addTouchSupport() {
      let startX = 0
      let currentX = 0
      let isDragging = false

      this.track.addEventListener("touchstart", (e) => {
        startX = e.touches[0].clientX
        isDragging = true
        this.stopAutoPlay()
      })

      this.track.addEventListener("touchmove", (e) => {
        if (!isDragging) return
        currentX = e.touches[0].clientX
        const diffX = startX - currentX

        if (Math.abs(diffX) > 50) {
          if (diffX > 0) {
            this.nextSlide()
          } else {
            this.prevSlide()
          }
          isDragging = false
        }
      })

      this.track.addEventListener("touchend", () => {
        isDragging = false
        this.startAutoPlay()
      })
    }

    updateCarousel() {
      if (this.isAnimating) return

      const cardWidth = this.cards[0].offsetWidth + 25 // width + gap
      const translateX = -this.currentSlide * cardWidth * this.cardsPerView
      this.track.style.transform = `translateX(${translateX}px)`

      // Update dots
      this.dots.forEach((dot, index) => {
        dot.classList.toggle("active", index === this.currentSlide)
      })

      // Update buttons
      this.prevBtn.disabled = this.currentSlide === 0
      this.nextBtn.disabled = this.currentSlide === this.totalSlides

      // Add animation class
      this.isAnimating = true
      setTimeout(() => {
        this.isAnimating = false
      }, 800)
    }

    nextSlide() {
      if (this.isAnimating) return

      if (this.currentSlide < this.totalSlides) {
        this.currentSlide++
      } else {
        this.currentSlide = 0 // Loop back to start
      }
      this.updateCarousel()
    }

    prevSlide() {
      if (this.isAnimating) return

      if (this.currentSlide > 0) {
        this.currentSlide--
      } else {
        this.currentSlide = this.totalSlides // Loop to end
      }
      this.updateCarousel()
    }

    goToSlide(slideIndex) {
      if (this.isAnimating || slideIndex === this.currentSlide) return

      this.currentSlide = slideIndex
      this.updateCarousel()
    }

    startAutoPlay() {
      this.autoPlayInterval = setInterval(() => {
        this.nextSlide()
      }, 5000)
    }

    stopAutoPlay() {
      clearInterval(this.autoPlayInterval)
    }
  }

  // Initialize carousel
  new SimpleCarousel()
})
