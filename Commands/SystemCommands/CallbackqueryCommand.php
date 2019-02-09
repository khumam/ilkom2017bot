<?php
/**
 * This file is part of the TelegramBot package.
 *
 * (c) Avtandil Kikabidze aka LONGMAN <akalongman@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Longman\TelegramBot\Commands\SystemCommands;

use Longman\TelegramBot\Commands\SystemCommand;
use Longman\TelegramBot\Entities\InlineKeyboard;
use Longman\TelegramBot\Request;

/**
 * Callback query command
 *
 * This command handles all callback queries sent via inline keyboard buttons.
 *
 * @see InlinekeyboardCommand.php
 */
class CallbackqueryCommand extends SystemCommand
{
    /**
     * @var string
     */
    protected $name = 'callbackquery';

    /**
     * @var string
     */
    protected $description = 'Reply to callback query';

    /**
     * @var string
     */
    protected $version = '1.1.1';

    /**
     * Command execute method
     *
     * @return \Longman\TelegramBot\Entities\ServerResponse
     * @throws \Longman\TelegramBot\Exception\TelegramException
     */

    public function execute()
    {
        $update         = $this->getUpdate();
        $callback_query = $update->getCallbackQuery();
        $callback_data  = $callback_query->getData();

         if ($callback_data=='Pilihekpedisi'){   
         	
			$inline_keyboard = new InlineKeyboard([
           ['text' => 'JNE', 'callback_data' => 'jne'],['text' => 'JNT', 'callback_data' => 'jnt'],
           ['text' => 'POS', 'callback_data' => 'next'],['text' => 'TIKI', 'callback_data' => 'next'],
        ],
        [
           ['text' => 'WAHANA', 'callback_data' => 'next'],
        ]);
        
        return Request::editMessageText([
            'chat_id'    => $callback_query->getMessage()->getChat()->getId(),
            'message_id' => $callback_query->getMessage()->getMessageId(),
            'text'       => $callback_data,
            'reply_markup' => $inline_keyboard,
            
        ]);
        
         }
    }
}
