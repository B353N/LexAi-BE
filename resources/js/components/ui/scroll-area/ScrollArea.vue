<script setup lang="ts">
import { type HTMLAttributes, computed } from 'vue'
import {
  ScrollAreaRoot,
  type ScrollAreaRootProps,
  ScrollAreaViewport,
  ScrollAreaScrollbar,
  ScrollAreaThumb,
  ScrollAreaCorner
} from 'radix-vue'
import { cn } from '@/lib/utils'

const props = withDefaults(defineProps<ScrollAreaRootProps & {
  class?: HTMLAttributes['class']
}>(), {})

const delegatedProps = computed(() => {
  const { class: _, ...delegated } = props
  return delegated
})
</script>

<template>
  <ScrollAreaRoot v-bind="delegatedProps" :class="cn('relative overflow-hidden', props.class)">
    <ScrollAreaViewport class="h-full w-full rounded-[inherit]">
      <slot />
    </ScrollAreaViewport>
    <ScrollAreaScrollbar
      class="flex touch-none select-none transition-colors"
      orientation="vertical"
    >
      <ScrollAreaThumb class="relative flex-1 rounded-full bg-border" />
    </ScrollAreaScrollbar>
    <ScrollAreaScrollbar
      class="flex touch-none select-none transition-colors"
      orientation="horizontal"
    >
      <ScrollAreaThumb class="relative flex-1 rounded-full bg-border" />
    </ScrollAreaScrollbar>
    <ScrollAreaCorner />
  </ScrollAreaRoot>
</template> 