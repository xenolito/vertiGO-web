/**
 * Testimonials module, based on Splide: https://splidejs.com
 * version: 1.0
 * @license Copyright 2008-2025, Oscar Rey Tajes. All rights reserved.
 * @author: Oscar Rey Tajes, oscar.rey.tajes@gmail.com
 * © @xenolito 2025
 * @requires @splidejs/splide
 * @requires HTML DOM specific layout, template needed at: https://swiperjs.com/get-started#add-swiper-html-layout
 *
 */

import { reverse } from 'lodash'
import { getConfigByAtt } from './attributesToConfigObj'
import Splide from '@splidejs/splide'

const hyphenToCamelcase = str => {
	return str.replace(/-([a-z])/g, k => k[1].toUpperCase())
}

const getRandom = (min, max) => {
	return Math.random() * (max - min) + min
}

document.addEventListener('DOMContentLoaded', () => {
	const attributeId = 'testimonials'
	const testimonialsContainer = document.querySelectorAll(`[data-${attributeId}]`)

	if (!testimonialsContainer.length) return

	const Testimonials = class {
		constructor(testimonialsContainer, config = {}) {
			const { log = false, nopagination = false, autoplay = false, arrows = false, customarrows = false, autoplayreverse = false, draggable = false, spacebetween = 32 } = config

			// console.log('repeat hardcoded', repeat)

			this.testimonialsContainer = testimonialsContainer
			this.nopagination = nopagination === 'true' || nopagination === '1' ? true : false // Hide or show bullets/pagination
			this.autoplay = autoplay ? Number(autoplay) : false
			this.arrows = arrows ? true : false
			this.customarrows = customarrows ? (document.querySelector(customarrows) ?? false) : false // from attribute: data-testimonials_customarrows. Must be a valid css selector (preferably an unique id)
			this.autoplayreverse = autoplayreverse ? true : false
			this.draggable = draggable === 'true' || draggable === '1' ? true : false
			this.spaceBetween = Number(spacebetween)

			this.log = log === 'true' || log === '1' ? true : false

			if (this.log) console.log(`log activated for ${this.testimonialsContainer} with class: ${this.testimonialsContainer.classList}`)

			this.init()
		}

		init = () => {
			this.setupDOM()
		}

		setupDOM = () => {
			this.testimonialsContainer.classList.add('splide')
			this.testimonialsContainer.setAttribute('role', 'group')
			this.testimonialsContainer.setAttribute('arial-label', 'testimonials slider')

			this.splideTrack = this.testimonialsContainer.querySelector('splide__track')
			if (!this.splideTrack) {
				this.splideTrack = this.testimonialsContainer.querySelector(':scope > *')
				if (this.splideTrack) {
					this.splideTrack.classList.add('splide__track')
					// this.splideTrack.style.setProperty('--slides-gap', `${this.spaceBetween}px`)
				} else {
					console.warn('⛔️ missing swiper-wrapper element for testimonials')
				}
			}

			this.slides = this.splideTrack.querySelectorAll('.splide__slide')
			if (!this.slides.length) {
				this.slides = this.splideTrack.querySelectorAll(':scope > *')

				const trackList = document.createElement('div')
				trackList.classList.add('splide__list')

				this.splideTrack.append(trackList)

				this.slides.forEach(slide => {
					slide.classList.add('splide__slide')
					trackList.append(slide)
				})
			}

			this.swiperLoopType = this.slides.length > 2 ? 'loop' : 'slide'

			if (this.slides.length < 2) {
				console.warn(`⛔️ missing slides for testimonials, only ${this.slides.length} found!`)
			}

			this.initSwiper()

			this.testimonialsContainer.style.opacity = '1'
		}

		initSwiper = () => {
			const config = {
				type: this.swiperLoopType,
				perPage: 2,
				perMove: 1,
				gap: 'clamp(2rem, 5vw, 4.8rem)',
				padding: 'clamp(5.6rem, 10vw, 9.6rem)',
				// padding: 'clamp(2.5rem, 10vw, 4rem)',
				arrows: this.arrows,
				pagination: !this.nopagination,
				// autoplay: true,
				// interval: 2500,
				breakpoints: {
					1000: {
						perPage: 1,
					},
					535: {
						perPage: 1,
						padding: '2rem',
						arrows: !this.customarrows, // if customarrows is set, arrows will be hidden on mobile
					},
				},
			}

			if (this.autoplay) {
				config.autoplay = true
				config.interval = this.autoplay
				config.pauseOnHover = true
			}

			this.splide = new Splide(this.testimonialsContainer, config).mount()

			if (this.customarrows) {
				const prevButton = this.customarrows.querySelector(':scope > :first-child')
				prevButton.style.cursor = 'pointer'
				prevButton.style.userSelect = 'none'
				// prevButton.style.pointerEvents = 'all'

				const nextButton = this.customarrows.querySelector(':scope > :last-child')
				nextButton.style.cursor = 'pointer'
				nextButton.style.userSelect = 'none'

				if (this.slides.length < 2) {
					prevButton.style.display = 'none'
					nextButton.style.display = 'none'
				}

				// nextButton.style.pointerEvents = 'all'

				// console.log('customarrows', prevButton, nextButton)

				if (prevButton) {
					prevButton.addEventListener('click', () => {
						this.splide.go('<')
					})
				}

				if (nextButton) {
					nextButton.addEventListener('click', () => {
						this.splide.go('>')
					})
				}
			}

			window.addEventListener('load', () => {
				this.splide.refresh()
			})
		}
	}

	testimonialsContainer.forEach(testimContainer => {
		const config = getConfigByAtt(testimContainer, attributeId, true)
		// console.log('CONFIG ', config)
		testimContainer.eventSelector = new Testimonials(testimContainer, config)
	})
})
