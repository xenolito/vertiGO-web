/**
 * Parallax — efecto parallax vertical relativo a la posición inicial del elemento.
 * El elemento NO cambia de posición al inicializar. El desplazamiento se acumula
 * proporcionalmente al scroll desde el momento en que se carga la página.
 *
 * Usage (Gutenberg block HTML attributes panel):
 *   data-parallax="0.3"  → depth 0.3 (por cada vh scrolled, mueve 0.3 vh)
 *   data-parallax="1"    → por cada vh scrolled, el elemento mueve 1 vh adicional
 *   data-parallax="0"    → sin movimiento
 *   data-parallax         → depth 0.5 por defecto
 *
 * Fórmula: y = -(scrollDelta × depth)
 *   El elemento se mueve depth px por cada px de scroll.
 *   depth=1 → 100% vh de travel sobre un scroll de 100vh.
 *
 * version: 1.1
 * @license Copyright 2008-2025, Oscar Rey Tajes. All rights reserved.
 * @author: Oscar Rey Tajes, oscar.rey.tajes@gmail.com
 * © @xenolito 2025
 * @requires gsap, lenis (via smooth_scroll.js)
 */

import { gsap } from 'gsap'
import { getConfigByAtt } from './attributesToConfigObj'

const attributeId = 'parallax'

const Parallax = class {
	constructor(el, config = {}) {
		this.el = el
		this.depth = parseFloat(config[attributeId]) || 0.5
		this._onScroll = null
		this._nativeScroll = null

		this.init()
	}

	init = () => {
		// Guardar scroll inicial → el offset se calcula SIEMPRE como delta desde aquí.
		// Garantiza y=0 en el primer frame, sin importar la posición del elemento en la página.
		const initialScroll = window.lenis?.scroll ?? window.scrollY ?? 0

		this._onScroll = ({ scroll }) => {
			gsap.set(this.el, { y: -(scroll - initialScroll) * this.depth })
		}

		if (window.lenis) {
			window.lenis.on('scroll', this._onScroll)
		} else {
			this._nativeScroll = () => this._onScroll({ scroll: window.scrollY })
			window.addEventListener('scroll', this._nativeScroll, { passive: true })
		}
	}

	destroy = () => {
		try {
			if (window.lenis && this._onScroll) {
				window.lenis.off('scroll', this._onScroll)
			}
			if (this._nativeScroll) {
				window.removeEventListener('scroll', this._nativeScroll)
			}
			gsap.set(this.el, { clearProps: 'transform' })
		} catch (err) {
			console.warn('⛔️ parallax: destroy error', err)
		} finally {
			try {
				delete this.el.parallax
			} catch (e) {
				this.el.parallax = undefined
			}
		}
	}
}

document.addEventListener('DOMContentLoaded', () => {
	const elements = document.querySelectorAll(`[data-${attributeId}]`)
	if (!elements.length) return

	elements.forEach(el => {
		const config = getConfigByAtt(el, attributeId, true)
		el.parallax = new Parallax(el, config)
	})
})
