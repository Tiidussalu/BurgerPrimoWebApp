<script setup>
import { Link } from '@inertiajs/vue3';

const currentYear = new Date().getFullYear();

</script>

<template>
        <footer class="border-t border-[#121212] mt-16 py-12 bg-[#121212]">
      <div class="max-w-7xl mx-auto px-6">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-8 mb-8">
          <div>
            <h3 class="text-[#D2691E] font-bold text-lg mb-4">Burger Primo</h3>
            <p class="text-gray-400 text-sm">
              Kirega valmistatud preemium burgerid, mida serveeritakse unkusega
            </p>
          </div>

          <div>
            <h3 class="text-white font-bold text-lg mb-4">Kiirlingid</h3>
            <ul class="space-y-2">
              <li>
                <Link href="/menu" class="text-gray-400 hover:text-[#D2691E] text-sm transition">
                  Menüü
                </Link>
              </li>
              <li>
                <Link href="/burger-builder" class="text-gray-400 hover:text-[#D2691E] text-sm transition">
                  Ehita burger
                </Link>
              </li>
              <li>
                <Link href="/orders" class="text-gray-400 hover:text-[#D2691E] text-sm transition">
                  Tellimused
                </Link>
              </li>
            </ul>
          </div>

          <div>
            <h3 class="text-white font-bold text-lg mb-4">Kontakt</h3>
            <ul class="space-y-2 text-gray-400 text-sm">
              <li>+372 5743 8483</li>
              <li>info@burgerprimo.ee</li>
              <li>Kauba tn 5/2<br>
                Kuressaare, Saaremaa 93819<br>        
                Eesti</li>
            </ul>
          </div>

          <div>
            <h3 class="text-white font-bold text-lg mb-4">Lahtiolekuajad</h3>
            <ul class="space-y-2 text-gray-400 text-sm">
              <li>E-N: 11:00 - 22:00</li>
              <li>R-L: 11:00 - 23:00</li>
                <li>P: 12:00 - 21:00</li>
            </ul>
          </div>
        </div>

        <div class="border-t border-[#0B0B0B] pt-8 text-center text-gray-400 text-sm">
          <p>&copy; {{ currentYear }} Burger Primo. Kõik õigused kaitstud.</p>
        </div>
      </div>
    </footer>
</template>