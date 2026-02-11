<template>
  <div ref="containerRef" class="burger-visualizer">
    <div v-if="isLoading" class="loading-overlay">
      <div class="loading-spinner"></div>
      <p>Preparing burger…</p>
    </div>
  </div>
</template>

<script setup lang="ts">
import { ref, onMounted, watch, onBeforeUnmount } from 'vue'
import * as THREE from 'three'
import { OrbitControls } from 'three/examples/jsm/controls/OrbitControls'
import { EffectComposer } from 'three/examples/jsm/postprocessing/EffectComposer'
import { RenderPass } from 'three/examples/jsm/postprocessing/RenderPass'
import { BokehPass } from 'three/examples/jsm/postprocessing/BokehPass'
import { RGBELoader } from 'three/examples/jsm/loaders/RGBELoader'
import type { SelectedIngredient, Ingredient } from '@/types/burger-types'

interface Props {
  selectedIngredients: Record<string, SelectedIngredient[]>
  ingredients: Record<string, Ingredient[]>
}

const props = defineProps<Props>()

const containerRef = ref<HTMLDivElement | null>(null)
const isLoading = ref(true)

let scene: THREE.Scene
let camera: THREE.PerspectiveCamera
let renderer: THREE.WebGLRenderer
let controls: OrbitControls
let burgerGroup: THREE.Group
let composer: EffectComposer
let animationId: number

const LAYER_HEIGHT = 0.26

/* ------------------------------------------------ */
/* TEXTURES (procedural but physically shaded) */
/* ------------------------------------------------ */

const createCanvasTexture = (draw: (ctx: CanvasRenderingContext2D) => void, size = 1024) => {
  const canvas = document.createElement('canvas')
  canvas.width = canvas.height = size
  const ctx = canvas.getContext('2d')!
  draw(ctx)
  const tex = new THREE.CanvasTexture(canvas)
  tex.colorSpace = THREE.SRGBColorSpace
  return tex
}

const bunTexture = createCanvasTexture(ctx => {
  const g = ctx.createRadialGradient(512, 512, 100, 512, 512, 512)
  g.addColorStop(0, '#f7c98c')
  g.addColorStop(1, '#c58a4f')
  ctx.fillStyle = g
  ctx.fillRect(0, 0, 1024, 1024)
})

const meatTexture = createCanvasTexture(ctx => {
  ctx.fillStyle = '#6a3b24'
  ctx.fillRect(0, 0, 1024, 1024)
})

const cheeseTexture = createCanvasTexture(ctx => {
  ctx.fillStyle = '#ffae3d'
  ctx.fillRect(0, 0, 1024, 1024)
})

/* ------------------------------------------------ */
/* MATERIALS — PHYSICAL FOOD SHADERS */
/* ------------------------------------------------ */

const materialFor = (name: string) => {
  const n = name.toLowerCase()

  if (n.includes('bun') || n.includes('sai')) {
    return new THREE.MeshPhysicalMaterial({
      map: bunTexture,
      roughness: 0.42,
      transmission: 0.22,
      thickness: 1.4,
      attenuationColor: new THREE.Color('#f2c27d'),
      attenuationDistance: 2.4,
      clearcoat: 0.45,
      clearcoatRoughness: 0.25
    })
  }

  if (n.includes('juust') || n.includes('cheese')) {
    return new THREE.MeshPhysicalMaterial({
      map: cheeseTexture,
      roughness: 0.28,
      transmission: 0.48,
      thickness: 0.6,
      attenuationColor: new THREE.Color('#ffb347'),
      clearcoat: 0.7,
      clearcoatRoughness: 0.15
    })
  }

  if (n.includes('liha') || n.includes('beef')) {
    return new THREE.MeshPhysicalMaterial({
      map: meatTexture,
      roughness: 0.75,
      metalness: 0.05,
      clearcoat: 0.25,
      clearcoatRoughness: 0.65
    })
  }

  return new THREE.MeshStandardMaterial({ color: 0xffffff })
}

/* ------------------------------------------------ */
/* GEOMETRY */
/* ------------------------------------------------ */

const geometryFor = (name: string) => {
  const n = name.toLowerCase()

  if (n.includes('bun') || n.includes('sai')) {
    return new THREE.CylinderGeometry(1.5, 1.45, 0.45, 96)
  }

  if (n.includes('liha') || n.includes('beef')) {
    return new THREE.CylinderGeometry(1.4, 1.35, 0.28, 96)
  }

  if (n.includes('juust') || n.includes('cheese')) {
    return new THREE.BoxGeometry(2.8, 0.06, 2.8, 12, 1, 12)
  }

  return new THREE.CylinderGeometry(1.2, 1.2, 0.2, 64)
}

/* ------------------------------------------------ */
/* SCENE */
/* ------------------------------------------------ */

const initScene = () => {
  scene = new THREE.Scene()

  camera = new THREE.PerspectiveCamera(
    28,
    containerRef.value!.clientWidth / containerRef.value!.clientHeight,
    0.1,
    100
  )
  camera.position.set(4.6, 3.1, 6.2)
  camera.lookAt(0, 1.8, 0)

  renderer = new THREE.WebGLRenderer({
    antialias: true,
    physicallyCorrectLights: true
  })
  renderer.setSize(containerRef.value!.clientWidth, containerRef.value!.clientHeight)
  renderer.setPixelRatio(Math.min(window.devicePixelRatio, 2))
  renderer.outputColorSpace = THREE.SRGBColorSpace
  renderer.toneMapping = THREE.ACESFilmicToneMapping
  renderer.toneMappingExposure = 1.15
  renderer.shadowMap.enabled = true

  containerRef.value!.appendChild(renderer.domElement)

  controls = new OrbitControls(camera, renderer.domElement)
  controls.enableDamping = true
  controls.enableRotate = false

  /* HDRI */
  new RGBELoader().load('/hdr/studio_food.hdr', hdr => {
    hdr.mapping = THREE.EquirectangularReflectionMapping
    scene.environment = hdr
  })

  /* LIGHTS */
  scene.add(new THREE.AmbientLight(0xffffff, 0.3))

  const key = new THREE.DirectionalLight(0xfff1dc, 1.6)
  key.position.set(6, 8, 5)
  key.castShadow = true
  scene.add(key)

  burgerGroup = new THREE.Group()
  scene.add(burgerGroup)

  composer = new EffectComposer(renderer)
  composer.addPass(new RenderPass(scene, camera))
  composer.addPass(new BokehPass(scene, camera, {
    focus: 3.2,
    aperture: 0.018,
    maxblur: 0.012
  }))

  animate()
  isLoading.value = false
}

/* ------------------------------------------------ */
/* BURGER BUILD */
/* ------------------------------------------------ */

const updateBurger = () => {
  burgerGroup.clear()

  let y = 0

  Object.values(props.selectedIngredients).flat().forEach(sel => {
    const ing = Object.values(props.ingredients).flat().find(i => i.id === sel.id)
    if (!ing) return

    for (let i = 0; i < sel.quantity; i++) {
      const mesh = new THREE.Mesh(
        geometryFor(ing.name),
        materialFor(ing.name)
      )

      mesh.position.y = y
      mesh.position.x += (Math.random() - 0.5) * 0.025
      mesh.position.z += (Math.random() - 0.5) * 0.025
      mesh.rotation.y += (Math.random() - 0.5) * 0.08
      mesh.castShadow = true

      burgerGroup.add(mesh)
      y += LAYER_HEIGHT
    }
  })

  burgerGroup.position.y = 1.5 - y / 2
}

/* ------------------------------------------------ */
/* LOOP */
/* ------------------------------------------------ */

const animate = () => {
  animationId = requestAnimationFrame(animate)
  controls.update()
  composer.render()
}

onMounted(() => {
  initScene()
  updateBurger()
})

watch(() => props.selectedIngredients, updateBurger, { deep: true })

onBeforeUnmount(() => {
  cancelAnimationFrame(animationId)
  renderer.dispose()
})
</script>

<style scoped>
.burger-visualizer {
  width: 100%;
  height: 100%;
  min-height: 600px;
  background: #2a1e16;
}

.loading-overlay {
  position: absolute;
  inset: 0;
  display: grid;
  place-items: center;
  background: black;
  color: white;
}
</style>
